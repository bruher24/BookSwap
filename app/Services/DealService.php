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
}
