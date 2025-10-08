<?php

namespace App\Services;

use App\DTO\Task\StoreDTO;
use App\DTO\Task\UpdateDTO;
use App\Helpers\DatabaseHelper;
use App\Repositories\TaskRepository;

class TaskService extends Service
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository(DatabaseHelper::getConnection());
    }

    public function getAllAsArray(string $sorted = null, ?string $statusForFilter = null): ?array
    {
        return $this->taskRepository->getAllAsArray($sorted, $statusForFilter) ?: null;
    }

    public function store(StoreDTO $dataDTO): int
    {
        return $this->taskRepository->create([
            'title' => $dataDTO->title,
            'description' => $dataDTO->description,
            'user_id' => $_SESSION['user_id']
        ]);
    }

    /**
     * @throws \Exception
     */
    public function update(UpdateDTO $dataDTO): void
    {
        $this->taskRepository->update([
            'admin_response' => $dataDTO->admin_response,
            'task_id' => $dataDTO->task_id,
            'status' => $dataDTO->status,
            'tags' => $dataDTO->tags
        ]);

    }
}