<?php

namespace App\Interfaces;

use App\Models\Filter;
use Override;

interface FilterServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Filter|false;

    #[Override]
    public function get(string $id): Filter|false;
}
