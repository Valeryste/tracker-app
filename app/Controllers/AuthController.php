<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTO\User\LoginDTO;
use App\DTO\User\RegisterDTO;
use App\Services\AuthService;
use App\Validators\UserValidator;
use JetBrains\PhpStorm\NoReturn;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    #[NoReturn]
    public function register(): void
    {
        $redirectPath = '/views/auth/register.php';
        if ($_SESSION['errors'] = (new UserValidator())->registration($_POST)) {
            $this->redirect($redirectPath);
        }

        $_SESSION['errors'] = $this->authService->register(new RegisterDTO(...$_POST));

        $this->redirect($redirectPath);
    }

    #[NoReturn]
    public function login(): void
    {
        $redirectPath = '/views/auth/login.php';
        if ($_SESSION['errors'] = (new UserValidator())->login($_POST)) {
            $this->redirect($redirectPath);
        }

        $_SESSION['response'] = $this->authService->login(new LoginDTO(...$_POST));

        if(!$_SESSION['response']['success']) {
            $this->redirect($redirectPath);
        }

        $this->redirect('/');
    }

    #[NoReturn]
    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /?success=Logged out successfully');
        exit;
    }


    #[NoReturn]
    private function redirect(string $path) : void
    {
        header("Location: $path");
        exit;
    }
}