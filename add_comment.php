<?php
    include('./check_login.php');
    include('./conn.php');
    include_once('./templates/Parsedown.php');

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

                $last_id = $conn->insert_id;

                $last_comments = "SELECT content FROM enter3017sky_comments WHERE id = $last_id";

                $result = $conn->query($last_comments);
                if ($result->num_rows > 0) {
                    $md = new Parsedown();
                    $md->setSafeMode(true);
                    $row = $result->fetch_assoc();
                    $text = $md->text($row['content']);
               
                    try {
                        $arr = array(
                            'result' => 'Success',
                            'message' => 'Successfully Added',
                            'user' => $user,
                            'Request_Method' => $_SERVER['REQUEST_METHOD'],
                            'parent_id' => $parent_id,
                            'content' => $content,
                            'id' => $last_id,
                            'nick' => "$nick",
                            'markdownContent' => $text,
                        );
                        echo json_encode($arr);
                    } catch (\Throwable $th) {
                        echo $th->error;
                    }

                }

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