<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;

class ErrorResponse extends ApiResponse
{
    public function __construct(string $message, int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null)
    {
        parent::__construct(
            message: $message,
            code: $code,
            data: $errors,
            success: false
        );
    }
}
