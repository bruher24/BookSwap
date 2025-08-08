<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ServiceInterface
{
    public function create(array $data): Model|false;

    public function get(int $id): Model|false;

    public function getMany(array $ids): Collection;

    public function getAll(): Collection;

    public function update(Model $object, array $data): bool;

    public function delete(Model $object): bool;

    public function getFilterFromRequest(Request $request): array;

}
