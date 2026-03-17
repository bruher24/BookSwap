<?php

namespace App\Interfaces;

use App\Models\Photo;
use Illuminate\Database\Eloquent\Collection;
use Override;

interface PhotoServiceInterface
{
    public function create(array $data): Photo|false;

    public function get(string $id): Photo|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Photo $photo, array $data): Photo|false;

    public function delete(Photo $photo): bool;
}
