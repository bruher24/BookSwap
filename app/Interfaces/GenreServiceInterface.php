<?php

namespace App\Interfaces;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

interface GenreServiceInterface
{
    public function create(array $data): Genre|false;

    public function get(string $id): Genre|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Genre $genre, array $data): Genre|false;

    public function delete(Genre $genre): bool;
}
