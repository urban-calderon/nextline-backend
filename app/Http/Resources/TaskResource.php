<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'due_date'    => $this->due_date->toIso8601String(),
            'comments'    => $this->comments,
            'tags'        => $this->tags,
            'user'        => new UserResource($this->whenLoaded('user')),
            'created_at'  => $this->created_at->toIso8601String(),
        ];
    }
}
