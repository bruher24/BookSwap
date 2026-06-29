<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\Book;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class UserService implements UserServiceInterface
{
    #[Override]
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

    #[Override]
    public function get(int $id): User|false
    {
        try {
            return User::findOrFail($id);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
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

    #[Override]
    public function where(string $field, string $value): Collection
    {
        try {
            return User::where($field, $value)
                ->withoutTrashed()
                ->get();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return new Collection();
        }
    }

    #[Override]
    public function update(User $user, array $data): User|false
    {
        try {
            if (isset($data['phone'])) {
                $data['phone'] = str_replace(' ', '', $data['phone']);
            }

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

    #[Override]
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

    #[Override]
    public function rate(User $user, User $rater, int $rate): bool
    {
        try {
            DB::beginTransaction();

            $user->ratings()->updateOrCreate(
                ['rater_id' => $rater->id],
                ['rate' => $rate]
            );

            $user->updateOrFail([
                'rating' => $user->ratings()->avg('rate') ?? 0
            ]);

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function favorites(User $user): Collection
    {
        try {
            return Cache::remember('favorites_' . $user->id, 600, function () use ($user) {
                Log::debug('Stored in cache: ' . 'favorites_' . $user->id);
                return $user->favorites()->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return new Collection();
        }
    }

    #[Override]
    public function addToFavorites(User $user, Book $book): bool
    {
        try {
            $user->favorites()->syncWithoutDetachingOrFail($book);
            Cache::forget('favorites_' . $user->id);

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }

    #[Override]
    public function removeFromFavorites(User $user, Book $book): bool
    {
        try {
            $user->favorites()->detachOrFail($book);
            Cache::forget('favorites_' . $user->id);

            return true;
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);

            return false;
        }
    }
}
