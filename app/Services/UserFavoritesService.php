<?php

namespace App\Services;

use App\Interfaces\UserFavoritesServiceInterface;
use App\Models\UsersFavoriteBooks;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;
use Throwable;

final class UserFavoritesService implements UserFavoritesServiceInterface
{
    #[Override]
    public function favorites(string $user_id): Collection
    {
        try {
            return Cache::remember('favorites_' . $user_id, 600, function () use ($user_id) {
                Log::debug('Stored in cache: ' . 'favorites_' . $user_id);
                return UsersFavoriteBooks::where('user_id', $user_id)->get();
            });
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            return new Collection();
        }
    }

    #[Override]
    public function addToFavorites(string $user_id, string $book_id): bool
    {
        DB::beginTransaction();
        try {
            $added = UsersFavoriteBooks::withTrashed()->updateOrCreate([
                'user_id' => $user_id,
                'book_id' => $book_id,
            ])->restore();

            if (!$added) {
                throw new Exception('Ошибка при добавлении в избранное');
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }

    #[Override]
    public function removeFromFavorites(string $user_id, string $book_id): bool
    {
        DB::beginTransaction();
        try {
            $record = UsersFavoriteBooks::where('user_id', $user_id)->where('book_id', $book_id)->first();

            if (!$record) {
                throw new Exception('Книга не найдена в избранном');
            }

            if (!$record->delete()) {
                throw new Exception('Ошибка при удалении из избранного');
            }

            DB::commit();
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
