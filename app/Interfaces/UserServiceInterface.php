<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function create(array $data): User|false;

    public function get(string $id): User|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(User $user, array $data): User|false;

    public function delete(User $user): bool;

    public function chats(User $user): Collection;

    public function getUnreadMessages(User $user): Collection|false;

    public function readMessages(User $user, array $messagesToRead): bool;
}
