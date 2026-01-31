<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;

class NotFoundResponse extends ApiResponse
{
    protected int $statusCode = Response::HTTP_NOT_FOUND;
    protected string $message = 'Resource not found';

    public function __construct()
    {
        parent::__construct(
            message: $this->message,
            code: $this->statusCode,
            success: false
        );
    }
}
