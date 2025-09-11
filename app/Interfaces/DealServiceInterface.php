<?php

namespace App\Interfaces;

use App\Models\Deal;

interface DealServiceInterface extends ServiceInterface
{
    public function create(array $data): Deal|false;

    public function get(string $id): Deal|false;
}
