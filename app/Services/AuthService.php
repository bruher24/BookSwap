<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class AuthService implements AuthServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(private readonly UserServiceInterface $userService)
    {
    }

    #[Override]
    public function login(array $credentials): bool
    {
        $remember = !!($credentials['remember'] ?? 0);
        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            return false;
        }
        return true;
    }

    #[Override]
    public function refreshToken(string $email): string
    {
        try {
            $user = $this->userService->where('email', $email)->first();
            if (!$user instanceof User) {
                throw new Exception('Пользователь с указанным email не найден');
            }
            $user->tokens()->delete();
            $abilities = [$email];
            if ($user->isAdmin()) {
                $abilities[] = 'admin';
            }
            return $user->createToken('api-token', $abilities, now()->addHours(2))->plainTextToken;
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return '';
        }
    }

    #[Override]
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
