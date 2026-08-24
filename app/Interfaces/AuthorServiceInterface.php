<?php

namespace App\Interfaces;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceInterface
{
    public function create(array $data): Author|false;

    public function get(int $id): Author|false;

    public function getAll(): Collection;

    public function update(Author $author, array $data): Author|false;

    public function delete(Author $author): bool;

    public function byUser(User $user): Collection;
}
