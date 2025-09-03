<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthService implements AuthServiceInterface
{
    public function __construct(private UserServiceInterface $userService)
    {
    }

    public function login(array $credentials): bool
    {
        if (!Auth::attempt($credentials, $credentials['remember'] ?? 0)) {
            return false;
        }
        return true;
    }

    public function refreshToken(string $email): string
    {
        try {
            $user = $this->userService->where('email', $email)->first();
            if (!$user instanceof User) {
                throw new Exception('Пользователь с указанным email не найден');
            }
            $user->tokens()->delete();
            return $user->createToken('api-token', ['*'], now()->addHour())->plainTextToken;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    public function logout(string $email): bool
    {
        try {
            $user = $this->userService->where('email', $email)->first();
            if (!$user instanceof User) {
                throw new Exception('Пользователь с указанным email не найден');
            }
            $user->tokens()->delete();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }
}
