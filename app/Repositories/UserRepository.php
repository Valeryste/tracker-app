<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRepository extends Repository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, password, is_admin, created_at, updated_at) 
            VALUES (:username, :password, :is_admin, NOW(), NOW())
        ");

        return $stmt->execute([
            'username' => $data['username'],
            'password' => $data['password'],
            'is_admin' => $data['is_admin'] ? 1 : 0
        ]);
    }

    public function usernameExists(string $username): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetchColumn() > 0;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        return $userData ?: null;
    }
}