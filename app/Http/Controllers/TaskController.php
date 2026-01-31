<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\Task\TaskService;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\DTOs\TaskDTO;
use App\Http\Resources\TaskResource;
use App\Http\Responses\SuccessfulResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): SuccessfulResponse
    {
        $tasks = $this->taskService->getAll($request->user());

        return new SuccessfulResponse(
            data: TaskResource::collection($tasks),
            message: 'Tasks retrieved successfully'
        );
    }

    public function store(CreateTaskRequest $request): SuccessfulResponse
    {
        $taskDTO = TaskDTO::fromRequest($request);
        $task = $this->taskService->create($taskDTO);

        return new SuccessfulResponse(
            data: new TaskResource($task),
            message: 'Task created successfully',
            code: 201
        );
    }

    public function show(Task $task): SuccessfulResponse
    {
        return new SuccessfulResponse(
            data: new TaskResource($task)
        );
    }

    public function update(UpdateTaskRequest $request, Task $task): SuccessfulResponse
    {
        $taskDTO = TaskDTO::fromRequest($request);
        $updatedTask = $this->taskService->update($task, $taskDTO);

        return new SuccessfulResponse(
            data: new TaskResource($updatedTask),
            message: 'Task updated successfully'
        );
    }

    public function destroy(Task $task): SuccessfulResponse
    {
        $this->taskService->delete($task);

        return new SuccessfulResponse(
            data: null,
            message: 'Task deleted successfully'
        );
    }

}
