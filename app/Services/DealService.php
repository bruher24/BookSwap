<?php

namespace App\Services;

use App\Interfaces\DealServiceInterface;
use App\Models\Deal;

class DealService extends Service implements DealServiceInterface
{
    public function __construct()
    {
        parent::__construct(Deal::class);
    }

    public function create(array $data): Deal|false
    {
        return parent::create($data);
    }

    public function get(int $id): Deal|false
    {
        return parent::get($id);
    }
}
