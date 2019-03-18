
<?php
    include_once('./check_login.php');
    include_once('./templates/Parsedown.php');
    include_once('./conn.php');
    include_once('./templates/utils.php');
    $timestamp = date('Y-m-d H:i:s');
?>

<!DOCTYPE html>
<html>
<?php 
    $title = "Message Board";
    include_once 'templates/head.php'
?>
<body>
    <header class="header">

<!-- 引入 navbar -->
<?php include_once 'templates/navbar.php' ?> 

    </header>
    <div class="container">

        <?php 
            if(isset($user)) {      // 顯示使用者有登入的畫面
                include_once 'templates/show_login.php';
            } else {                // 顯示使用者未登入的畫面
                $user = null;
                include_once 'templates/show_unlogin.php';
            } 
        ?>

        <!-- 計算頁數 & 撈主留言 -->
        <?php 
            
            $page = 1;
            if(isset($_GET['page']) && !empty($_GET['page'])) {
                $page = (int) $_GET['page'];
            }
            $size = 10;
            $start = $size * ($page - 1);

                // 主留言
            $sql_comments =
                "SELECT comments.id, comments.content,comments.username, comments.created_at, users.nickname 
                FROM enter3017sky_comments AS comments 
                LEFT JOIN enter3017sky_user AS users 
                ON comments.username = users.username 
                WHERE comments.parent_id = 0
                ORDER BY comments.id DESC
                LIMIT ?, ?
                ";

            $stmt = $conn->prepare($sql_comments);
            $stmt->bind_param("ii", $start, $size);
            $is_success = $stmt->execute();
            $result = $stmt->get_result();
            
            if($is_success) {
                $md = new Parsedown(); // 文字轉成 markdown 格式
                $md->setSafeMode(true); 
                while($row = $result->fetch_assoc()) {
                    $text = $md->text($row['content']);
        ?>  
                <!-- 主留言開始 -->
            <div class='comments__wrapper'>
                <div class="meg__wrap card border-dark mb-2"> 
                    <div class="comment__header card-header">
                        <div class="comment__header__left">
                            <div class="comment__author nick">
                                <?= escape($row['nickname']) ?>
                            </div> 
                            <div class="comment__timestamp">
                                <?= $row['created_at']?>
                            </div>
                        </div>
                        <div class="comment__header__right">
                            <?php
                                if ($user === $row['username']) {
                                    echo renderDelAndEditBtn($row['id']);
                                }
                            ?>
                        </div>
                    </div>
                    <div class="comment__content ">
                        <?= $text ?>
                    </div>
                    <!-- 主留言結束 -->

                        <?php
                            // 設定使用者在自己的主留言底下留言顯示不同顏色
                            // 子留言
                            // sql 語法裡面不能放 $row['id']; 所以給它一個變數
                            $parent_id = $row['id'];
                            $sql_sub_comments = 
                                "SELECT c.id, c.content, c.created_at,c.username, u.nickname
                                FROM enter3017sky_comments AS c
                                LEFT JOIN enter3017sky_user AS u
                                ON c.username = u.username 
                                WHERE c.parent_id = ?
                                ORDER BY c.id ASC";

                            $stmt_sub = $conn->prepare($sql_sub_comments);
                            $stmt_sub->bind_param("i", $parent_id);
                            $stmt_sub->execute();
                            $result_sub = $stmt_sub->get_result();

                            if($result_sub){
                                while($row_sub = $result_sub->fetch_assoc()){

                                    $md = new Parsedown(); // 文字轉成 markdown 格式
                                    $md->setSafeMode(true); 
                                    $text_sub = $md->text($row_sub['content']);
                        ?>
                        <?php 
                            if (
                                $user === $row_sub['username'] && 
                                $row['username'] === $row_sub['username']) { 
                        ?>
                            <div class="sub-meg__user alert alert-light mb-2"> 
                        <?php 
                            } else { 
                        ?>
                            <div class="sub-meg__wrap sub-meg__user card border-dark mb-2">
                        <?php 
                            } 
                        ?>
                            <div class="comment__header card-header">
                                <div class="comment__header__left">
                                    <div class="sub-comment__author"><?= escape($row_sub['nickname']) ?></div> 
                                    <div class="sub-comment__timestamp"><?= $row_sub['created_at']?></div>
                                </div>
                                <div class="comment__header__right">
                                    <?php // 判斷是否為使用者，才有刪除編輯按鈕
                                        if (isset($_SESSION['username']) && $_SESSION['username'] === $row_sub['username']) {
                                            echo renderDelAndEditBtn($row_sub['id']);
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="sub-comment__content card-body">
                                <?= $text_sub ?>
                            </div>
                        </div>

                        <?php
                                }
                            }
                        ?>

                <div class="wrapper__form rounded-bottom w-100">
                    <form class="meg__form createSubMsg" method="POST" action="./add_comment.php">
                        <input type="hidden" class="hidden" name="parent_id" value="<?= $parent_id; ?>">
                        <input type="hidden" class="hidden" name="nickname" value="<?php if(isset($nick)) echo $nick; ?>" /><!-- 如果有使用者(登入)的話，才印出 -->
                        <div class='form-row'>
                            <textarea class="content form-control" name='content' type='textarea' placeholder="留言內容"></textarea>
                        </div>
                        <div class='form-row'>

                            <?php if(isset($nick)) { ?><!-- 如果有使用者(登入)的話，才顯示 -->
                                <div class="sub__btn">
                                    <input type="submit" class="submit__btn btn btn-primary">
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-danger mb-0" role="alert">
                                    請註冊或登入！
                                </div>
                            <?php }?>

                        </div>
                    </form>
                </div>
            </div>
        </div>

            <?php
                    }
                }   
            ?>

        <div class="filler"></div>
    </div>

    <?php
        include_once 'templates/footer.php'
    ?>

</body>
</html>