<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\User\LoginDTO;
use App\DTO\User\RegisterDTO;
use App\Models\User;
use App\Repositories\UserRepository;

class AuthService extends Service
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        $user = new User(
            $userData['username'],
            $userData['password'],
            (bool)$userData['is_admin']
        );

        $user->setId((int)$userData['id']);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['is_admin'] = $user->isIsAdmin();

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