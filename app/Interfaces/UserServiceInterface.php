<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function create(array $data): User|false;

    public function get(int $id): User|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(User $user, array $data): User|false;

    public function delete(User $user): bool;

    public function rate(User $user, User $rater, int $rate): bool;
}
