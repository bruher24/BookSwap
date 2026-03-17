<?php

namespace App\Interfaces;

use App\Models\BookType;
use Illuminate\Database\Eloquent\Collection;

interface BookTypeServiceInterface
{
    public function create(array $data): BookType|false;

    public function get(string $id): BookType|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(BookType $bookType, array $data): BookType|false;

    public function delete(BookType $bookType): bool;
}
