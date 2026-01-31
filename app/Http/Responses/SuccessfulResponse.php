<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;

class SuccessfulResponse extends ApiResponse
{
    public function __construct(mixed $data, string $message = 'Successful operation', int $code = Response::HTTP_OK)
    {
        parent::__construct(
            message: $message,
            code: $code,
            data: $data,
            success: true
        );
    }
}
