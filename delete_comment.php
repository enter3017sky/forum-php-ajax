<?php

    include_once('./check_login.php');
    require_once('./conn.php');
    require_once('./templates/utils.php');

    if (
        isset($_POST['id']) && 
        !empty($_POST['id'])
    ){
        $id = $_POST['id'];
        // 刪除主留言，或子留言
        $sql = 
        "DELETE FROM enter3017sky_comments 
        WHERE (id = ? or parent_id = ?)
        AND username = ?";
        // 增加判斷條件，使用者 id 符合才能刪除文章
        /* $parent_id 是數字，所以不用引號框起來 */

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $id, $id, $user);
        if ($stmt->execute()) {
            echo json_encode(array(
                'result' => 'Delete Success',
                'message' => 'Good Job！刪除成功！'
            ));
        } else {
            echo json_encode(array(
                'result' => 'Fail',
                'message' => 'Delete Fail'
            ));
        }
    } else {
        echo json_encode(array(
            'result' => 'SQL no execute',
            'message' => 'SQL no execute'
        ));
    }
?>