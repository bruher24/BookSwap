<?php

namespace App\Interfaces;

use App\Models\Genre;
use Override;

interface GenreServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Genre|false;

    #[Override]
    public function get(string $id): Genre|false;
}
