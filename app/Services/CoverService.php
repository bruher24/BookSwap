<?php

namespace App\Services;

use App\Interfaces\CoverServiceInterface;
use App\Models\Cover;
use Override;

class CoverService extends Service implements CoverServiceInterface
{
    public function __construct()
    {
        parent::__construct(Cover::class);
    }

    #[Override]
    public function create(array $data): Cover|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Cover|false
    {
        return parent::get($id);
    }
}
