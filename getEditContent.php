<?php

include_once('./check_login.php');
require_once('./conn.php');
require_once('./templates/utils.php');

if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    $getCommentByIdQuery = "SELECT content FROM enter3017sky_comments WHERE id = $id";

    $result = $conn->query($getCommentByIdQuery);

    try {
        //code...
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $originContent = $row['content'];
            $arr = array(
                'result' => 'Success',
                'message' => 'Successfully Added',
                'originContent' => $originContent,
            );
            echo json_encode($arr);
        } else {
            echo json_encode(array(
                'result' => 'Fail',
                'message' => 'Added Failure'
            ));
            echo $conn->error;
        }
    } catch (\Throwable $th) {
        //throw $th;
        echo $th->error;
    }

}

?>