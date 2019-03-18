<?php
    // 登出後導回首頁。
    // setcookie("token", '', time()+3600*24);

    session_start();
    session_unset();

    //註釋： session_destroy()將重置session，您將失去所有已存儲的session數據。
    session_destroy();
    header('Location: ./index.php');
?>