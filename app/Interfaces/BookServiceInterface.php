<?php

namespace App\Interfaces;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface extends ServiceInterface
{
    public function create(array $data): Book|false;

    public function get(int $id): Book|false;

    public function attach(Book $book, array $authors): bool;
}
