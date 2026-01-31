<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\DTOs\RegisterUserDTO;
use App\DTOs\LoginDTO;
use App\Http\Resources\UserResource;
use App\Http\Responses\SuccessfulResponse;
use App\Http\Responses\UnauthorizedResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register new user
     * @param CreateUserRequest $request
     * @return SuccessfulResponse
     */
    public function register(CreateUserRequest $request): SuccessfulResponse
    {
        $user = $this->authService->create(RegisterUserDTO::fromRequest($request));

        return new SuccessfulResponse(
            data: new UserResource($user),
            message: 'User registered successfully',
            code: 201
        );
    }

    /**
     * Login user and return JWT token
     * @param LoginUserRequest $request
     * @return SuccessfulResponse|UnauthorizedResponse
     */
    public function login(LoginUserRequest $request)
    {
        $loginDTO = LoginDTO::fromRequest($request);

        $tokenData = $this->authService->login($loginDTO);

        if (! $tokenData) {
            return new UnauthorizedResponse();
        }

        return new SuccessfulResponse(
            data: $tokenData,
            message: 'Login successful'
        );
    }

    /**
     * Logout the user (invalidate the token)
     * @return SuccessfulResponse
     */
    public function logout(): SuccessfulResponse
    {
        $this->authService->logout();

        return new SuccessfulResponse(
            data: null,
            message: 'Successfully logged out'
        );
    }

    /**
     * Refresh the JWT token
     * @return SuccessfulResponse
     */
    public function refresh(): SuccessfulResponse
    {
        $tokenData = $this->authService->refresh();

        return new SuccessfulResponse(
            data: $tokenData,
            message: 'Token refreshed'
        );
    }
}
