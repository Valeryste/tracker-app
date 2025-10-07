<?php

declare(strict_types = 1);

namespace App\Models;

class User extends Model
{
    private int $id;
    private string $username;
    private string $password;
    private bool $is_admin;

    public function __construct(string $username, string $password, bool $is_admin = false) {
        $this->username = $username;
        $this->password = $password;
        $this->is_admin = $is_admin;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

}