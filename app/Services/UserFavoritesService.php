<?php

namespace App\Services;

use App\Interfaces\UserFavoritesServiceInterface;
use App\Models\Book;
use App\Models\User;
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
    public function favorites(User $user): Collection
    {
        try {
            return Cache::remember('favorites_' . $user->id, 600, function () use ($user) {
                Log::debug('Stored in cache: ' . 'favorites_' . $user->id);
                $bookIds = UsersFavoriteBooks::where('user_id', $user->id)->pluck('book_id');
                return Book::whereIn('id', $bookIds)->get();
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
            DB::beginTransaction();

            $added = UsersFavoriteBooks::withTrashed()->updateOrCreate([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ])->restore();

            if (!$added) {
                throw new Exception('Ошибка при добавлении в избранное');
            }

            DB::commit();
            Cache::forget('favorites_' . $user->id);
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }

    #[Override]
    public function removeFromFavorites(User $user, Book $book): bool
    {
        try {
            DB::beginTransaction();

            $record = UsersFavoriteBooks::where('user_id', $user->id)->where('book_id', $book->id)->first();

            if (!$record) {
                throw new Exception('Книга не найдена в избранном');
            }

            if (!$record->delete()) {
                throw new Exception('Ошибка при удалении из избранного');
            }

            DB::commit();
            Cache::forget('favorites_' . $user->id);
            return true;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), ['exception' => $e]);
            return false;
        }
    }
}
