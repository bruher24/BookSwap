<?php

namespace App\Interfaces;

use App\Models\Filter;

interface FilterServiceInterface extends ServiceInterface
{
    public function create(array $data): Filter|false;

    public function get(int $id): Filter|false;
}
