<?php
    include('./check_login.php');
    include('./conn.php');

    try {
        if (isset($_POST['content']) && !empty($_POST['content'])) {

            $content = $_POST['content'];
            $parent_id = $_POST['parent_id'];
            $nick = $_SESSION['nickname'];

            // 新增留言
            $sql = "INSERT INTO enter3017sky_comments(username, content, parent_id) VALUES(?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $user, $content, $parent_id);

            if ($stmt->execute()) {
                //  取得最後一筆輸入的 id

                $last_id = $conn->insert_id;
                // if($parent_id === '0'){
                    $arr = array(
                        'result' => 'Success',
                        'message' => 'Successfully Added',
                        'user' => $user,
                        'Request_Method' => $_SERVER['REQUEST_METHOD'],
                        'parent_id' => $parent_id,
                        'content' => $content,
                        'id' => $last_id,
                        'nick' => $nick
                    );
                    echo json_encode($arr);
            } else {
                echo json_encode(array(
                    'result' => 'Fail',
                    'message' => 'Added Failure'
                ));
                echo $conn->error;
            };

        } else {
            echo json_encode(array(
                'result' => 'SQL no execute',
                'message' => 'SQL no execute'
            ));
        };
    } catch (\Throwable $th) {
        throw $th;
    }



?>