<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthService implements AuthServiceInterface
{
    public function auth(array $credentials): string
    {
        try {
            if (!Auth::validate($credentials)) {
                throw new Exception('Ошибка авторизации');
            }
            return $this->createToken($credentials['email']);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    public function login(array $credentials): bool
    {
        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $credentials['remember'])) {
            return false;
        }
        return true;
    }

    public function currentUser(): ?User
    {
        return Auth::user();
    }

    public function logout(): void
    {
        Auth::user()->tokens()->delete();
        Auth::logout();
    }

    private function createToken(string $email): string
    {
        try {
            $user = User::where('email', $email)->firstOrFail();
            $user->tokens()->delete();
            return $user->createToken('api-token')->plainTextToken;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return '';
        }
    }
}
