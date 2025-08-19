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
}
