<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

abstract class ApiResponse implements Responsable
{
    public function __construct(
        protected string $message,
        protected int $code,
        protected mixed $data = null,
        protected bool $success = true
    ) {}

    public function toResponse($request): JsonResponse
    {
        $payload = [
            'success' => $this->success,
            'message' => $this->message,
            'data'    => $this->data,
        ];

        if (is_null($this->data)) {
            unset($payload['data']);
        }

        return response()->json($payload, $this->code);
    }
}
