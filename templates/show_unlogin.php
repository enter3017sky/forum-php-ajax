<?php
    include_once('./check_login.php');
?>

<div class='wrapper__form card border-danger mb-3'>
    <div class='card-header'>
        Please <a href='./register.php' class='alert-link badge badge-danger'>register</a> or <a href='./login.php' class='alert-link badge badge-danger'>login</a>.
    </div> 
    <div class='alert alert-dismissible alert-danger alert__box fade show'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        <strong>Oops!
        </strong>請<a href='./register.php' class='alert-link'>註冊</a>或<a href='./login.php' class='alert-link '>登入</a>！
    </div>
</div>