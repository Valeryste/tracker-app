<?php
declare(strict_types=1);
namespace App\Controllers;

use App\DTO\Task\StoreDTO;
use App\DTO\Task\UpdateDTO;
use App\Services\StatusService;
use App\Services\TaskService;
use App\Validators\TaskValidator;

class TaskController extends Controller
{
    private TaskService $taskService;
    private StatusService $statusService;

    public function __construct()
    {
        $this->taskService = new TaskService();
        $this->statusService = new StatusService();
    }

    public function index() : false|string
    {
       return json_encode([
            'success' => true,
            'data' => $this->taskService->getAllAsArray(
                $_GET['sort'] ?? '' ,
                $_GET['status'] ?? null
                ) ?? [],
            'statuses' => $this->statusService->getAllAsArray()
        ]);
    }

    public function store(): false|string
    {
        $data = $_POST;

        if($errors = (new TaskValidator())->create($data))
        {
            return json_encode([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        return json_encode([
            'success' => true,
            'task_id' =>$this->taskService->store((new StoreDTO(...$data)))
        ]);
    }

    public function update(): false|string
    {
        $data = $_POST;

        $this->taskService->update(
            (new UpdateDTO(...$data))
        );

        return json_encode([
            'success' => true,
        ]);
    }
}