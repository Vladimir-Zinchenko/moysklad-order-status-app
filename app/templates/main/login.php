<?php

use App\Util\AppHelper;

require AppHelper::templatePath('common/header.php')
?>

<div id="login-container">
    <form id="login-from">
        <div>
            <label for="login">Логин</label>
            <input class="ui-input" type="text" id="login" name="login" required>
        </div>
        <div>
            <label for="password">Пароль</label>
            <input class="ui-input" type="password" id="password" name="password" required>
        </div>
        <div>
            <button class="button button--success" id="login-action">Авторизоваться</button>
        </div>
        <div class="error">Неправильный логин или пароль</div>
    </form>
</div>

<?php require AppHelper::templatePath('common/footer.php') ?>
