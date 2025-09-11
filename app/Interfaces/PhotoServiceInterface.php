<?php

namespace App\Interfaces;

use App\Models\Photo;

interface PhotoServiceInterface extends ServiceInterface
{
    public function create(array $data): Photo|false;

    public function get(string $id): Photo|false;
}
