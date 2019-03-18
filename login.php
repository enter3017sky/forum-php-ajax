
<!DOCTYPE html>
<html>
<?php 
    $title = "Login « Message Board";
    include_once 'templates/head.php'
?>
<body>
    <header>
            <!-- 引入 navbar -->
        <?php include 'templates/navbar.php' ?>
    </header>
    <div class="container">
        <!-- 用 POST 方法，submit 之後傳送給 handle_register.php -->
        <form class="register__form" method="POST" action="./handle_login.php">
            <div class='form-row form-inline'>
                <label>帳號：<input type="text" name="username" class="form-control" autofocus></label>
            </div>
            <div class='form-row form-inline'>
                <label>密碼：<input type="password" name="password" class="form-control"></label>
            </div>
            <div class='form-row form-inline'>
                <input type="submit" class="LR__btn btn btn-primary">
            </div>
        </form>
    </div>
</body>
</html>