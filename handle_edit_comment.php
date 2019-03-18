<?php
    include_once('./check_login.php');
    require_once('./conn.php');
    require_once('./utils.php');

    if (isset($_POST['content']) && !empty($_POST['content'])){
        $content = $_POST['content'];
        $id = $_POST['id'];
        // $username = $_COOKIE['username'];

            // 新增留言
        $sql = "UPDATE enter3017sky_comments
                SET content = ?
                WHERE id = ?
                AND username = ?"; // 判斷條件，是使用者才可編輯
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $content, $id , $user);
        if ($stmt->execute()) {
            // server redirect ，PHP的重新導向
            // header('location:./index.php');
                echo json_encode(array(
                    'result' => 'Edit Message Success',
                    'message' => 'Good Job！成功更新留言！'
                ));
        } else {
        // $_SERVER['HTTP_REFERER'] 導回原本的地方
            // printMessage($conn->error, $_SERVER['HTTP_REFERER']);
            echo json_encode(array(
                'result' => 'Fail',
                'message' => 'Edit Message Fail',
                // 'resp' => $user
            ));
        }
    } else {
        // printMessage('請輸入內容!!', $_SERVER['HTTP_REFERER']);
        echo json_encode(array(
            'result' => 'SQL no execute',
            'message' => 'SQL no execute'
        ));
    }
?>