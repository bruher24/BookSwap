<?php

namespace App\Services;

use App\Interfaces\AuthServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
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
    public function verifyEmail(string $userId): bool
    {
        try {
            $user = $this->userService->get($userId);

            if (!$user instanceof User) {
                throw new Exception('Пользователь с указанным email не найден');
            }

            if ($user->hasVerifiedEmail()) {
                return true;
            }

            $user->markEmailAsVerified();
            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
