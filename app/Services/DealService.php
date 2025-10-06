<?php

namespace App\Services;

use App\Interfaces\DealServiceInterface;
use App\Models\Deal;
use Override;

class DealService extends Service implements DealServiceInterface
{
    public function __construct()
    {
        parent::__construct(Deal::class);
    }

    #[Override]
    public function create(array $data): Deal|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Deal|false
    {
        return parent::get($id);
    }
}
