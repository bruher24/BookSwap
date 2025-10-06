<?php

namespace App\Services;

use App\Interfaces\FilterServiceInterface;
use App\Models\Filter;
use Override;

class FilterService extends Service implements FilterServiceInterface
{
    public function __construct()
    {
        parent::__construct(Filter::class);
    }

    #[Override]
    public function create(array $data): Filter|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Filter|false
    {
        return parent::get($id);
    }
}
