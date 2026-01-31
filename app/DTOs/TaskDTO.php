<?php

namespace App\DTOs;

use Illuminate\Http\Request;

readonly class TaskDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public string $status,
        public string $due_date,
        public ?int $user_id,
        public ?string $comments = null,
        public ?array $tags = []
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            status: $request->validated('status', 'todo'),
            due_date: $request->validated('due_date'),
            user_id: $request->user()->id,
            comments: $request->validated('comments'),
            tags: $request->validated('tags')
        );
    }
}
