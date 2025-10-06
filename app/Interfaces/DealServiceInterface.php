<?php

namespace App\Interfaces;

use App\Models\Deal;
use Override;

interface DealServiceInterface extends ServiceInterface
{
    #[Override]
    public function create(array $data): Deal|false;

    #[Override]
    public function get(string $id): Deal|false;
}
