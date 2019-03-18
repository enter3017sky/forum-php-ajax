<?php
    // 登入的提示訊息都做在同一個頁面上了，這個就不需要了
    // 使用者在 register.php 輸入的 username 
    // 用這個 $_POST['username']可以取得資料

    // 1. 產生session id 並放到 cookie
    session_start(); // 啟用 PHP 內建的 session

    require_once('./conn.php');
    require_once('./utils.php');

    if (
        isset($_POST['username']) && 
        isset($_POST['password']) && 
        !empty($_POST['username'])&& 
        !empty($_POST['password'])
    ){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM enter3017sky_user 
                WHERE username =?";
        $stmt = $conn->prepare($sql);
        // $result = $conn->query($sql);
        $stmt->bind_param("s", $username);

            // 如果沒有 執行結果
        if (!$stmt->execute()) {
            echo $conn->error;
            // printMessage($conn->error, './login.php');
            exit();
        }
            // 用 get_result 拿結果
        $result = $stmt->get_result();
 
        if ($result->num_rows <= 0) {
            printMessage('帳號或密碼錯誤!!', './login.php');
            exit();
        }
        
        $row = $result->fetch_assoc();
        // var_dump($row);
        // exit();

        if (password_verify($password, $row['password'])) {
            // setToken($conn, $username); 
            // 用 php 的 session，自己做的就可以不用了 
            // 寫一個資料進去 session
            $_SESSION['username'] = $username;
            $_SESSION['nickname'] = $row['nickname']; 
            // 從 $row 取得 nickname 然後給 $_SESSION['nickname']
            // 2. $username 放在記憶體裡面
            printMessage('登入成功', './index.php');
        } else {
            printMessage('帳號或密碼錯誤!!', './login.php');
            exit();
        }
    } else {
        printMessage('請輸入帳號或密碼!!', './login.php');
    }
?>