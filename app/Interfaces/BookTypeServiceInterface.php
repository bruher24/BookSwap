<?php

namespace App\Interfaces;

use App\Models\BookType;
use Override;

interface BookTypeServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): BookType|false;

    #[Override]
    public function get(string $id): BookType|false;

    #[Override]
    public function update(string $id, array $data): BookType|false;
}
