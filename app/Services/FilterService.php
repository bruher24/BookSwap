<?php

namespace App\Services;

use App\Interfaces\FilterServiceInterface;
use App\Models\Filter;
use Override;

final class FilterService extends Service implements FilterServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
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

    #[Override]
    public function update(string $id, array $data): Filter|false
    {
        return parent::update($id, $data);
    }
}
