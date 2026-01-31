<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;

class UnauthorizedResponse extends ApiResponse
{
    public function __construct(string $message = 'Incorrect credentials')
    {
        parent::__construct(
            message: $message,
            code: Response::HTTP_UNAUTHORIZED,
            success: false
        );
    }
}
