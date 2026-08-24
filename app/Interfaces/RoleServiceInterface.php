<?php

namespace App\Interfaces;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

interface RoleServiceInterface
{
    public function create(array $data): Role|false;

    public function get(int $id): Role|false;

    public function getAll(): Collection;

    public function update(Role $role, array $data): Role|false;

    public function delete(Role $role): bool;
}
