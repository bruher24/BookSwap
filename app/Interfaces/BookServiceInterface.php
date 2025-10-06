<?php

namespace App\Interfaces;

use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Override;

interface BookServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Book|false;

    #[Override]
    public function get(string $id): Book|false;

    public function attach(string $book_id, array $authors): bool;
}
