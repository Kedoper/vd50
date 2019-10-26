<?php
session_start();

/* Если в пользователь нажал на ссылку для выхода*/
if ($_GET['logout'] === "true") {
    /*Удаляем сессию*/
    session_destroy();
    /*Отправляем заголовок для перехода в эту дерикторию
    Чтобы get параметров не было*/
    header('Location: /auth');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация + Регистрация</title>
    <link rel="stylesheet" href="../styles/main.css">
</head>
<body>
<header class="page-header">
    <nav class="page-navbar">
        <div class="page-navbar__logo">
            <span>VD50</span>
        </div>
        <div class="page-navbar__title">
            <a href="/auth/">Авторизация и регистрация</a>
        </div>
        <ul class="navbar">
            <!-- Тут условие, если нет сессии, то вывести это-->
            <?php if (empty($_SESSION['logged_user'])): ?>
                <li class="navbar-item">
                    <span class="link-emulate" onclick="showLoginForm()">Войти</span>
                </li>
                <li class="navbar-item">
                    <span class="link-emulate" onclick="showRegisterForm()">Регистрация</span>
                </li>
                <!--Иначе, если есть сессия, то вывести логин пользователя и ссылку для выхода-->
            <?php else: ?>
                <li class="navbar-item">
                    <span class="link-emulate"><?php echo $_SESSION['logged_user']['login'] ?></span>
                    <span class="link-emulate">
                        <a href="?logout=true">Выйти</a>
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<section class="page-wrap">
    <!--Если сессия пуста, то отобразить форму входа и регистрации-->
    <div class="form-wrap <?php if (!empty($_SESSION['logged_user'])) echo 'hide'; ?>">
        <div class="info-row p0">
            <div class="info-block mr0 hide" id="form_message">
                <div class="info-block__header">
                    <span>Сообщение</span>
                </div>
                <div class="info-block__body">
                    <p>
                        Some text
                    </p>
                </div>
            </div>
        </div>

        <form action="javascript:void(0)" id="login" class="form">
            <div class="form-row">
                <label for="user_login">Введите ваш логин</label>
                <input type="text" name="user_login" id="user_login" required>
            </div>
            <div class="form-row">
                <label for="user_password">Введите ваш пароль</label>
                <input type="password" name="user_password" id="user_password" required>
            </div>
            <div class="form-row">
                <button type="submit">Войти</button>
            </div>
        </form>

        <form action="javascript:void(0)" id="register" class="form hide">
            <div class="form-row">
                <label for="new_user_login">Придумайте ваш логин</label>
                <input type="text" name="new_user_login" id="new_user_login" required>
            </div>
            <div class="form-row">
                <label for="new_user_password">Придумайте ваш пароль</label>
                <input type="password" name="new_user_password" id="new_user_password" required>
            </div>
            <div class="form-row">
                <button type="submit">Зарегистрироваться</button>
            </div>
        </form>
    </div>

    <div class="content <?php if (empty($_SESSION['logged_user'])) echo 'hide'; ?>">
        <p>Ура, эта хрень работает! Кстати, привет <?php echo $_SESSION['logged_user']['login']; ?></p>
    </div>
</section>
<script src="./index.js"></script>
</body>
</html>