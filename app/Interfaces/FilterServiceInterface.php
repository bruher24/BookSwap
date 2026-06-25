<?php

namespace App\Interfaces;

use App\Models\Filter;
use Illuminate\Database\Eloquent\Collection;

interface FilterServiceInterface
{
    public function create(array $data): Filter|false;

    public function get(int $id): Filter|false;

    public function getAll(): Collection;

    public function where(string $field, string $value): Collection;

    public function update(Filter $filter, array $data): Filter|false;

    public function delete(Filter $filter): bool;
}
