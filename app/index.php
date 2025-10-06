<?php

use App\Config\Database;

require_once __DIR__ . '/../vendor/autoload.php';

echo "Hello is ";

try {
    Database::getConnection();
} catch (Exception $e) {
}

echo "PHP Version: " . phpversion() . "<br>";
?>