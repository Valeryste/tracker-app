<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "Hello from " . (getenv('APP_NAME') ?: 'Tracker App') . "!<br>";

// Получение настроек из переменных окружения
$db_host = getenv('DB_HOST') ?: 'db';
$db_name = getenv('MYSQL_DATABASE') ?: 'tracker_app';
$db_user = getenv('MYSQL_USER') ?: 'user';
$db_pass = getenv('MYSQL_PASSWORD') ?: 'password';

try {
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name}",
        $db_user,
        $db_pass,
        [
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => false
        ]
    );
    echo "✅ Connected to MySQL successfully!<br>";
} catch (PDOException $e) {
    echo "❌ MySQL Connection failed: " . $e->getMessage() . "<br>";
}

echo "PHP Version: " . phpversion() . "<br>";
?>