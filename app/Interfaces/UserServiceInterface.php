<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface extends ServiceInterface
{
    public function updateSettings(User $user, array $data): bool;

    public function addToFavorites($user_id, $book_id): void;

    public function removeFromFavorites($user_id, $book_id): void;

    public function getChats(User $user): Collection;

    public function getMessages(User $user, User $recipient): Collection;
}