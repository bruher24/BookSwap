<?php

namespace App\Interfaces;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceInterface
{
    public function create(array $data): Author|false;

    public function get(int $id): Author|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Author $author, array $data): Author|false;

    public function delete(Author $author): bool;
}
