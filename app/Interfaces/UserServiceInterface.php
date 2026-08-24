<?php

namespace App\Interfaces;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function create(array $data): User|false;

    public function get(int $id): User|false;

    public function getAll(): Collection;

    public function update(User $user, array $data): User|false;

    public function delete(User $user): bool;

    public function rate(User $user, User $rater, int $rate): User | bool;

    public function favorites(User $user): Collection;

    public function addToFavorites(User $user, Book $book): bool;

    public function removeFromFavorites(User $user, Book $book): bool;

    public function verifyEmail(int $userId): bool;
}
