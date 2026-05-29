<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

final class UserService implements UserServiceInterface
{
    public function create(array $data): User|false
    {
        try {
            DB::beginTransaction();
            $user = new User($data);

            if (!$user->save()) {
                throw new Exception("Ошибка при создании типа");
            }

            DB::commit();
            return $user->refresh();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function get(string $id): User|false
    {
        try {
            return User::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function getAll(): Collection
    {
        try {
            return Cache::remember(User::CACHE_KEY, 600, function (): Collection {
                Log::debug('Stored in cache: ' . User::CACHE_KEY);
                return User::all();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function where(string $field, string $value): Collection
    {
        try {
            return User::where($field, $value)->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    public function update(User $user, array $data): User|false
    {
        try {
            if (isset($data['phone_number'])) {
                $phoneNumber = str_replace(' ', '', $data['phone_number']);
                if ($user->phone()->exists()) {
                    $user->phone()->update(['number' => $phoneNumber]);
                } else {
                    $user->phone()->create(['number' => $phoneNumber]);
                }
            }

            unset($data['phone_number']);

            if (isset($data['password'])) {
                if (!Hash::check($data['old_password'], $user->getAuthPassword())) {
                    throw new Exception('Старый пароль указан неверно');
                }

                if ($data['password'] == null) {
                    unset($data['password']);
                }
            }

            if (!$user->update($data)) {
                throw new Exception('Ошибка при обновлении пользователя');
            }

            return $user->refresh();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    public function delete(User $user): bool
    {
        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
