<?php

namespace App\Interfaces;

use App\Models\Genre;

interface GenreServiceInterface extends ServiceInterface
{
    public function create(array $data): Genre|false;

    public function get(string $id): Genre|false;
}
