<?php

declare(strict_types=1);

namespace App\Validators;

class UserValidator extends Validator
{
    public function registration(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        } else if (strlen($data['username']) < 3) {
            $errors['username'] = 'Username must be at least 3 characters';
        } else if (strlen($data['username']) > 100) {
            $errors['username'] = 'Username must not exceed 100 characters';
        } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors['username'] = 'Username can only contain letters, numbers and underscores';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } else if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        return $errors;
    }

    public function login(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }

        return $errors;
    }
}