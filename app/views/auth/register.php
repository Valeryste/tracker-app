<?php
session_start();
$title = "Регистрация";
require_once '../layout/header.php';
?>

    <form class="card" action="/actions/auth/register.php" method="post">
        <h2>Регистрация</h2>

        <div class="form-group">
            <label for="username">
                Имя
                <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Красавчик92"
                        class="form-control"
                        required
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                >
            </label>
            <div class="error"><?php echo $_SESSION['errors']['username'] ?? ''; ?></div>
        </div>

        <div class="form-group">
            <label for="password">
                Пароль
                <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="******"
                        class="form-control"
                        required
                >
            </label>
            <div class="error"><?php echo $_SESSION['errors']['password'] ?? ''; ?></div>
        </div>

        <div class="error"><?php echo $_SESSION['errors']['message'] ?? ''; ?></div>

        <button type="submit" class="btn-primary" id="submit">Продолжить</button>
    </form>

    <div class="text-center">
        <p>У меня уже есть <a href="login.php">аккаунт</a></p>
    </div>

<?php
$_SESSION['errors'] = [];
require_once '../layout/footer.php';
?>