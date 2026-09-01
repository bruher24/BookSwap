<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\Book;
use App\Models\Role;
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
            return DB::transaction(function () use ($data) {
                $user = User::create($data);
                $user->roles()->attachOrFail(Role::where('name', 'user')->firstOrFail());
                return $user->refresh();
            });
        } catch (Throwable $e) {
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
            }

            $user->updateOrFail($data);
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
            return !!$user->deleteOrFail();
        } catch (Throwable $e) {
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function rate(User $user, User $rater, int $rate): User | bool
    {
        try {
            return DB::transaction(function () use ($user, $rater, $rate) {
                $user->ratings()->updateOrCreate(
                    ['rater_id' => $rater->id],
                    ['rate' => $rate]
                );

                $user->updateOrFail([
                    'rating' => $user->ratings()->avg('rate') ?? 0
                ]);

                return $user->refresh();
            });
        } catch (Throwable $e) {
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
            if ($book->user()->is($user)) {
                throw new Exception('Нельзя добавить в избранное собственную книгу');
            }

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

    #[Override]
    public function verifyEmail(int $userId): bool
    {
        try {
            $user = $this->get($userId);

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
