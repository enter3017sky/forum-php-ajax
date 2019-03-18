
<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand  mb-0" href="#">Message Board</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarColor02">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="./index.php"> Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
                <!-- 先檢查有沒有設定，就不會跳(沒有這個變數)錯誤訊息 -->
                <?php if(isset($user) && !empty($user)) { ?>
            <li class="nav-item">
                <a class="nav-link" href="./logout.php">登出</a>
            </li>
                <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="./register.php">註冊</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./login.php">登入</a>
            </li>
                <?php } ?>
        </ul>
    </div>
</nav>