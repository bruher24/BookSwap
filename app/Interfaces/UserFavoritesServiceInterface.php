<?php

namespace App\Interfaces;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserFavoritesServiceInterface
{
    public function favorites(User $user): Collection;

    public function addToFavorites(User $user, Book $book): bool;

    public function removeFromFavorites(User $user, Book $book): bool;
}
