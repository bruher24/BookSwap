<?php

namespace App\Interfaces;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Model;
use Override;

interface GenreServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Genre|false;

    #[Override]
    public function get(string $id): Genre|false;

    #[Override]
    public function update(string $id, array $data): Genre|false;
}
