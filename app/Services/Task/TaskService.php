<?php

namespace App\Services\Task;

use App\Models\Task;
use App\Models\User;
use App\DTOs\TaskDTO;

class TaskService
{

    public function getAll(User $user)
    {
        return $user->tasks()->latest()->get();
    }

    public function create(TaskDTO $taskDTO): Task
    {
        return Task::create([
            'title'       => $taskDTO->title,
            'description' => $taskDTO->description,
            'status'      => $taskDTO->status,
            'due_date'    => $taskDTO->due_date,
            'comments'    => $taskDTO->comments,
            'tags'        => $taskDTO->tags,
            'user_id'     => $taskDTO->user_id,
        ]);
    }

    public function update(Task $task, TaskDTO $taskDTO): Task
    {
        $task->update([
            'title'       => $taskDTO->title,
            'description' => $taskDTO->description,
            'status'      => $taskDTO->status,
            'due_date'    => $taskDTO->due_date,
            'comments'    => $taskDTO->comments,
            'tags'        => $taskDTO->tags,
            'user_id'     => $taskDTO->user_id,
        ]);

        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
