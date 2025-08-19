<?php

namespace App\Services;

use App\Interfaces\FilterServiceInterface;
use App\Models\Filter;

class FilterService extends Service implements FilterServiceInterface
{
    public function __construct()
    {
        parent::__construct(Filter::class);
    }

    public function create(array $data): Filter|false
    {
        return parent::create($data);
    }

    public function get(int $id): Filter|false
    {
        return parent::get($id);
    }
}
