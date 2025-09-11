<?php

namespace App\Interfaces;

use App\Models\Author;

interface AuthorServiceInterface extends ServiceInterface
{
    public function create(array $data): Author|false;

    public function get(string $id): Author|false;
}
