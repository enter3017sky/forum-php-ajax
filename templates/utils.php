<?php
        // 印出訊息，導引至某處。
    function printMessage($msg, $redirect) {
        echo '<script>';
        echo "alert('" . htmlentities($msg, ENT_QUOTES) . "');";
        echo "window.location = '" . $redirect . "'";
        echo '</script>';
    }


    // escape function 跳脫字元
    function escape($str) {
        return htmlspecialchars($str, ENT_QUOTES);
    }

        // 實作 session 的機制
    function setToken($conn, $username) {
        // 使用 uniqid()函式 生成 id 給 token 用
        $token = uniqid();
        $sql = "DELETE FROM enter3017sky_certificates WHERE username='$username'";
        $conn->query($sql);
        
        $sql = "INSERT INTO enter3017sky_certificates(username, token)VALUES('$username', '$token')";
        $conn->query($sql);
        setcookie("token", $token, time()+3600*24);
    }
    
    function getUserByToken($conn, $token) {
        if(isset($token) && !empty($token)) {
            $sql = "SELECT c.username, c.token, u.nickname
            FROM enter3017sky_certificates AS c
            LEFT JOIN enter3017sky_users AS u
            ON c.username = u.username
            WHERE token = '$token'";
    
            $result = $conn->query($sql);
            if(!$result || $result->num_rows <= 0){
                return null;
            } else {
                $row = $result->fetch_assoc();
                return $row['username'];
                // return $row['nickname'];
            }
        } else {
            return null;
        }
    }

    
    function renderDelAndEditBtn($id) {
        return "
        <div class='btn-group' role='group'>
            <input type='button' class='delete__btn btn btn-outline-danger' data-id='$id' value='刪除' />
            <input type='button' class='edit__btn btn btn-outline-success' data-id='$id' value='編輯'/>
        </div>
        ";
    }

?>