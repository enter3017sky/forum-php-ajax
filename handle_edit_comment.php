<?php
    include_once('./check_login.php');
    require_once('./conn.php');
    require_once('./templates/utils.php');
    include_once('./templates/Parsedown.php');

    if (isset($_POST['content']) && !empty($_POST['content'])) {
        $content = $_POST['content'];
        $id = $_POST['id'];

        $sql = "UPDATE enter3017sky_comments
                SET content = ?
                WHERE id = ?
                AND username = ?"; // 判斷條件，是使用者才可編輯
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sis", $content, $id, $user);
        if ($stmt->execute()) {
            $get_edit_completed_content = "SELECT content FROM enter3017sky_comments WHERE id = $id";

            $result = $conn->query($get_edit_completed_content);
            if ($result->num_rows > 0) {
                $md = new Parsedown();
                $md->setSafeMode(true);
                $row = $result->fetch_assoc();
                $text = $md->text($row['content']);

                echo json_encode(array(
                    'result' => 'Edit Message Success',
                    'message' => 'Good Job！成功更新留言！',
                    'content' => $text
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
    }
?>