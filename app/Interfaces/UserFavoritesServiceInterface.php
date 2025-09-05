<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserFavoritesServiceInterface
{
    public function favorites(string $user_id): Collection;

    public function addToFavorites(string $user_id, string $book_id): bool;

    public function removeFromFavorites(string $user_id, string $book_id): bool;
}
