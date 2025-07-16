<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;

class UserService extends Service implements UserServiceInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function updatePhone(User $user, string $phoneNumber): bool
    {
        try {
            $phoneNumber = str_replace(' ', '', $phoneNumber);
            if ($user->phone()->exists()) {
                $user->phone()->update(['number' => $phoneNumber]);
            } else {
                $user->phone()->create(['number' => $phoneNumber]);
            }
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return false;
        }
        return true;
    }
}