<?php

namespace App\Interfaces;

use App\Models\Book;
use Override;

interface BookServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Book|false;

    #[Override]
    public function get(string $id): Book|false;

    public function attach(string $book_id, array $authors): bool;

    #[Override]
    public function update(string $id, array $data): Book|false;
}
