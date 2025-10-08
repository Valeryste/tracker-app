<?php
declare(strict_types=1);
namespace App\Validators;

class TaskValidator extends Validator
{
    public function store(array $data) : array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'title is required';
        } else if (strlen($data['title']) < 3) {
            $errors['title'] = 'title must be at least 3 characters';
        } else if (strlen($data['title']) > 100) {
            $errors['title'] = 'title must not exceed 100 characters';
        } else if (!preg_match('/^[a-zA-Z0-9_]+$/', $data['title'])) {
            $errors['title'] = 'title can only contain letters, numbers and underscores';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'description is required';
        } else if (strlen($data['description']) < 6) {
            $errors['description'] = 'description must be at least 6 characters';
        }

        return $errors;
    }

    public function update(array $data): array
    {
        $errors = [];

        if (
            $data['admin_response'] !== '' &&
            !preg_match('/^[a-zA-Z0-9_]+$/', $data['admin_response'])
        ) {
            $errors['admin_response'] = 'admin response can only contain letters, numbers and underscores';
        }

        if (!is_string($data['status'])) {
            $errors['status'] = 'Status can only be a string or null';
        }

        return $errors;
    }

}