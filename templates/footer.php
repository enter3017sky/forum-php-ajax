<?php
    // 計算主留言的數量，來計算頁數
    $count_sql = "SELECT count(*) AS count 
    FROM enter3017sky_comments
    WHERE parent_id = 0";

    $stmt_count = $conn->prepare($count_sql);
    $is_count_success = $stmt_count->execute();
    $count_result = $stmt_count->get_result();

    if ($is_count_success && $count_result->num_rows > 0) {
        $count = $count_result->fetch_assoc()['count'];
        $size = 10;
        $total_page = ceil($count / $size);
    }

?>

<footer class='bottom__footer bg-dark'>
    <ul class="pagination pagination-sm mx-auto footer__ul">
        <?php 
            // 設定上一頁符號的顯示狀態
            if($page === 1) {
                echo "
                <li class='page-item disabled'>
                    <a class='page-link' href='#'>&laquo;</a>
                </li>" ;
            } else {
                echo "
                <li class='page-item'>
                    <a class='page-link' href='#'>&laquo;</a>
                </li>" ;
            }
            for($i = 1; $i <= $total_page; $i++) {
                if ($page === 1 && $i <= 5) {
                    if($page === $i) {
                        echo "
                        <li class='page-item active'>
                            <a class='page-link' href='#'>" . $i . "</a>
                        </li>";
                    } else {
                        echo "
                        <li class='page-item'>
                            <a class='page-link' href='./index.php?page=$i'>" . $i . "</a>
                        </li>" ;
                    }
                } else if ($page === 2 && $i <= 5) {
                    if($page === $i) {
                        echo "
                        <li class='page-item active'>
                            <a class='page-link' href='#'>" . $i . "</a>
                        </li>";
                    } else {
                        echo "
                        <li class='page-item'>
                            <a class='page-link' href='./index.php?page=$i'>" . $i . "</a>
                        </li>" ;
                    }
                } else if( $page-3 < $i && $page+3 > $i) {
                    if($page === $i) {
                        echo "
                        <li class='page-item active'>
                            <a class='page-link' href='#'>" . $i . "</a>
                        </li>";
                    } else {
                        echo "
                        <li class='page-item'>
                            <a class='page-link' href='./index.php?page=$i'>" . $i . "</a>
                        </li>" ;
                    }
                }
            } 

            // 設定下一頁符號的顯示狀態
            if($page == $total_page ) {
                echo "
                <li class='page-item disabled'>
                    <a class='page-link' href='#'>&raquo;</a>
                </li>" ;
            } else {
                echo "
                <li class='page-item'>
                    <a class='page-link' href='#'>&raquo;</a>
                </li>" ;
            }  
        ?>
    </ul>
</footer>

    <!-- High Light -->
    <script src="/static/prism.js"></script>
