<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthService implements AuthServiceInterface
{
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

    public function logout(): bool
    {
        try {
            $this->currentUser()->tokens()->delete();
            Auth::logout();
            return true;
        } catch (Throwable $e) {
            Log::error($e);
            return false;
        }
    }

    public function currentUser(): ?User
    {
        return Auth::user();
    }

    public function refreshToken(User $user): string
    {
        // TODO: userservice ?
        try {
            $user->tokens()->delete();
            return $user->createToken('api-token')->plainTextToken;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return '';
        }
    }
}
