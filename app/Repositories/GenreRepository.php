<?php

namespace App\Repositories;

use App\Models\Genre;

class GenreRepository extends Repository
{
    public function __construct()
    {
        parent::__construct(new Genre());
    }
}
