<?php

namespace App\Interfaces;

use App\Models\Cover;

interface CoverServiceInterface extends ServiceInterface
{
    public function create(array $data): Cover|false;

    public function get(int $id): Cover|false;
}
