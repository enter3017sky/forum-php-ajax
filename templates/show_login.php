<?php
    include_once('./check_login.php');
?>

<div class='wrapper__form card border-dark mb-2'>
    <form class='meg__form createMsg' method='POST' action='./add_comment.php'>
        <input type='hidden' class='hidden' name='parent_id' value='0' />
        <input type='hidden' class='hidden' name='nickname' value='<?= $nick ?>' />
        <div class='user__nick card-header'>
            Hello, <?= $nick ?>
        </div>
        <div class='form-row'>
            <textarea class='content form-control' name='content' placeholder='留言內容'></textarea>
        </div>
        <div class='form-row'>
            <div class='sub__btn'>
                <input type='submit' class='submit__btn btn btn-primary'>
            </div>
        </div>
    </form>
</div>