<?php

namespace App\Interfaces;

use App\Models\BookType;

interface BookTypeServiceInterface extends ServiceInterface
{
    public function create(array $data): BookType|false;

    public function get(int $id): BookType|false;
}
