<?php
declare(strict_types=1);
namespace App\DTO\Task;

use App\DTO\DTO;

class UpdateDTO extends DTO
{
    public function __construct(
        public readonly string $task_id,
        public readonly string $status,
        public readonly ?string $admin_response = null,
        public readonly ?array $tags = null
    ) {}

}