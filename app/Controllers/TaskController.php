<?php
declare(strict_types=1);
namespace App\Controllers;

use App\DTO\Task\TaskDTO;
use App\Services\TaskService;
use App\Validators\TaskValidator;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService();
    }

    public function index() : false|string
    {
        return json_encode([
            'success' => true,
            'data' => $this->taskService->getAllAsArray() ?? []
        ]);
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        if($errors = (new TaskValidator())->create($data))
        {
            return json_encode([
                'success' => false,
                'errors' => $errors,
            ]);
        }

        return json_encode([
            'success' => true,
            'task_id' =>$this->taskService->create((new TaskDTO(...$data)))
        ]);
    }


}