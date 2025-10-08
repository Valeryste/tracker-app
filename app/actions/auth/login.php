<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Controllers\AuthController;
$controller = new AuthController();
$controller->login();