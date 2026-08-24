<?php

namespace App\Interfaces;

use App\Models\Cover;
use Illuminate\Database\Eloquent\Collection;

interface CoverServiceInterface
{
    public function create(array $data): Cover|false;

    public function get(int $id): Cover|false;

    public function getAll(): Collection;

    public function delete(Cover $cover): bool;
}
