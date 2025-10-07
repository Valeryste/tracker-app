<?php
session_start();
$title = "Главная";
require_once 'views/layout/header.php';
?>

    <div class="nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="actions/auth/logout.php">Выйти</a>
        <?php else: ?>
            <a href="views/login.php">Войти</a>
            <a href="views/register.php">Регистрация</a>
        <?php endif; ?>
    </div>

    <div class="card text-center">
        <h1>Добро пожаловать в Tracker App</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p class="mt-2">Здравствуйте, <?php echo $_SESSION['username'] ?></p>
        <?php else: ?>
            <p class="mt-2">Пожалуйста, войдите или зарегистрируйтесь</p>
        <?php endif; ?>
    </div>

<?php require_once 'views/layout/footer.php'; ?>