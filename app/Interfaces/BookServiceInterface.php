<?php

namespace App\Interfaces;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function create(array $data): Book|false;

    public function get(int $id): Book|false;

    public function getAll(): Collection;

    public function update(Book $book, array $data): Book|false;

    public function delete(Book $book): bool;

    public function filtered(array $filters): Collection;

    public function byUser(User $user): Collection;
}
