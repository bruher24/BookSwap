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

    public function byUser(User $user, array $conditions = []): array;

    public function byAuthor(Author $author, array $conditions = []): array;

    public function byGenre(Genre $genre, array $conditions = []): array;

    public function where(array $conditions = []): Collection;

    public function attach(Book $book, array $authors): bool;
}
