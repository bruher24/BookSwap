<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\Setting;
use App\Models\User;
use App\Models\UsersFavoriteBooks;
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

    public function updateSettings(User $user, array $data): bool
    {
        try {
            $setting = $user->settings()->where('name', $data['setting_name']);
            if ($setting->first()) {
                $id = $setting->first()->id;
                $setting->updateExistingPivot($id, ['value' => $data['setting_value'] ?? 'off']);
            } else {
                $toAttach = Setting::where('name', $data['setting_name'])->first();
                $user->settings()->attach($toAttach->id, ['value' => $data['setting_value'] ?? 'off']);
            }
        } catch (Exception $exception) {
            logger($exception->getMessage());
            return false;
        }
        return true;
    }

    public function addToFavorites($user_id, $book_id): void
    {
        UsersFavoriteBooks::withTrashed()->updateOrCreate([
            'user_id' => $user_id,
            'book_id' => $book_id,
        ])->restore();
    }

    public function removeFromFavorites($user_id, $book_id): void
    {
        $record = UsersFavoriteBooks::where('user_id', $user_id)->where('book_id', $book_id)->first();
        $record->delete();
    }
}