<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends Service implements UserServiceInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

    public function update(Model $object, array $data): bool
    {
        if (!$object instanceof User) {
            return false;
        }

        $user = $object;

        if (isset($data['phone_number'])) {
            try {
                $phoneNumber = str_replace(' ', '', $data['phone_number']);
                if ($user->phone()->exists()) {
                    $user->phone()->update(['number' => $phoneNumber]);
                } else {
                    $user->phone()->create(['number' => $phoneNumber]);
                }
            } catch (Exception $exception) {
                logger($exception->getMessage());
                return false;
            }
        }

        unset($data['phone_number']);

        if (isset($data['password']) && !Hash::check($data['old_password'], $user->getAuthPassword())) {
            return false;
        }

        if (!parent::update($user, $data)) {
            return false;
        }

        return true;
    }
}