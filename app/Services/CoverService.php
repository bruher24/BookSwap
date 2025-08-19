<?php

namespace App\Services;

use App\Interfaces\CoverServiceInterface;
use App\Models\Cover;

class CoverService extends Service implements CoverServiceInterface
{
    public function __construct()
    {
        parent::__construct(Cover::class);
    }

    public function create(array $data): Cover|false
    {
        return parent::create($data);
    }

    public function get(int $id): Cover|false
    {
        return parent::get($id);
    }
}
