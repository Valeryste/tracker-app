<?php
declare(strict_types=1);
namespace App\DTO\Task;

use App\DTO\DTO;

class TaskDTO extends DTO
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $admin_response = null
    ) {}

}