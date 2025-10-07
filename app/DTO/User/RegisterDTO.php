<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\DTO\DTO;

class RegisterDTO extends DTO
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly bool $is_admin = false
    ) {}
}