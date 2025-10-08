<?php
session_start();
$title = "Главная";
require_once 'views/layout/header.php';
?>

    <div class="container">
        <div class="nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="actions/auth/logout.php" class="btn-logout">Выйти</a>
            <?php else: ?>
                <a href="views/login.php" class="btn-login">Войти</a>
                <a href="views/register.php" class="btn-register">Регистрация</a>
            <?php endif; ?>
        </div>

        <div class="welcome-card">
            <h1>Добро пожаловать в Tracker App</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p class="welcome-text">Здравствуйте,
                    <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
            <?php else: ?>
                <p class="welcome-text">Пожалуйста, войдите или зарегистрируйтесь</p>
            <?php endif; ?>
        </div>


    </div>
<?php if (isset($_SESSION['user_id'])): ?>
    <?php require_once 'views/task/dashboard.php'; ?>
<?php else: ?>
    <div class="alert info-alert text-center">
        <h4>Для просмотра заявок необходимо авторизоваться</h4>
        <p>Пожалуйста, войдите в систему чтобы увидеть таблицу с заявками.</p>
    </div>
<?php endif; ?>

<?php require_once 'views/layout/footer.php'; ?>