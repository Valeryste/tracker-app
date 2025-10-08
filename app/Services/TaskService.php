<?php

namespace App\Services;

use App\Config\Database;
use App\DTO\Task\TaskDTO;
use App\Repositories\TaskRepository;

class TaskService extends Service
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository(Database::getConnection());
    }

    public function getAllAsArray(): ?array
    {
        return $this->taskRepository->getAllAsArray() ?: null;
    }

    public function create(TaskDTO $dataDTO): int
    {
        return $this->taskRepository->create([
            'title' => $dataDTO->title,
            'description' => $dataDTO->description,
            'user_id' => $_SESSION['user_id']
        ]);
    }
}