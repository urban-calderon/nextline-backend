<?php

namespace App\Services\Auth;

use App\Models\User;
use App\DTOs\RegisterUserDTO;
use App\DTOs\LoginDTO;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Create a new user
     * * @param DTOs\RegisterUserDTO $registerUserDTO
     * @return User
     */
    public function create(RegisterUserDTO $registerUserDTO): User
    {
        return User::create([
            'name'     => $registerUserDTO->name,
            'email'    => $registerUserDTO->email,
            'password' => Hash::make($registerUserDTO->password),
        ]);
    }

    /**
     * Login user and return a JWT token
     * @param DTOs\LoginDTO $loginDTO
     * @return array|null
     */
    public function login(LoginDTO $loginDTO): ?array
    {
        $credentials = [
            'email'    => $loginDTO->email,
            'password' => $loginDTO->password
        ];

        if (! $token = JWTAuth::attempt($credentials)) {
            return null;
        }

        return $this->formatTokenStructure($token);
    }

    /**
     * Logout the user (invalidate the token)
     * @return void
     */
    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

     /**
     * Refresh the JWT token
     * @return array
     */
    public function refresh(): array
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->formatTokenStructure($token);
    }

    /**
     * Private method for standardising the token structure
     * @param string $token
     * @return array
     */
    private function formatTokenStructure(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTAuth::factory()->getTTL() * 60,
        ];
    }
}
