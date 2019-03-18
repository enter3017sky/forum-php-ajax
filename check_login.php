<?php
    session_start(); // 1.可以從 cookie 拿到 PHPSESSID
    include_once('./conn.php');
    include_once('./templates/utils.php');

    // 2. 拿 PHPSESSID 去查
    if (isset($_SESSION['username'])) {
        $user = $_SESSION['username'];
        $nick = $_SESSION['nickname'];
    } else {
        $user = null;
    }

?>