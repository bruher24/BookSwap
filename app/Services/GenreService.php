<?php

namespace App\Services;

use App\Repositories\GenreRepository;

class GenreService extends Service
{
    public function __construct()
    {
        parent::__construct(new GenreRepository());
    }
}
