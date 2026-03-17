<?php

namespace App\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function create(array $data): Book|false;

    public function get(string $id): Book|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Book $book, array $data): Book|false;

    public function delete(Book $book): bool;

    public function attach(Book $book, array $authors): bool;
}
