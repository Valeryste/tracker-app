<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\User\LoginDTO;
use App\DTO\User\RegisterDTO;
use App\Helpers\DatabaseHelper;
use App\Repositories\UserRepository;

class AuthService extends Service
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(DatabaseHelper::getConnection());
    }

    public function register(RegisterDTO $registerDTO): array
    {
        if ($this->userRepository->usernameExists($registerDTO->username)) {
            return ['success' => false, 'message' => 'Username already exists'];
        }

        $repositoryData = [
            'username' => $registerDTO->username,
            'password' => password_hash($registerDTO->password, PASSWORD_DEFAULT),
            'is_admin' => $registerDTO->is_admin
        ];

        if ($this->userRepository->create($repositoryData)) {
            return ['success' => true, 'message' => 'Registration successful'];
        }

        return ['success' => false, 'message' => 'Registration failed'];
    }

    public function login(LoginDTO $userDTO): array
    {
        $userData = $this->userRepository->findByUsername($userDTO->username);

        if (!$userData) {
            return [
                'success' => false,
                'errors' => 'Invalid credentials'
            ];
        }

        if (!password_verify($userDTO->password, $userData['password'])) {
            return [
                'success' => false,
                'errors' => 'Invalid credentials'
            ];
        }

        $_SESSION['user_id'] = (int)$userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['is_admin'] = (bool)$userData['is_admin'];

        return [
            'success' => true
        ];
    }

    public function logout(): void
    {
        $this->startSession();
        session_destroy();
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}