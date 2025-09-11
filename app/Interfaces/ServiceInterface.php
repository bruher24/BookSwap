<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ServiceInterface
{
    public function create(array $data): Model|false;

    public function get(string $id): Model|false;

    public function getMany(array $ids): Collection;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(string $id, array $data): bool;

    public function delete(string $id): bool;

}
