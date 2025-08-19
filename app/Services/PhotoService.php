<?php

namespace App\Services;

use App\Interfaces\PhotoServiceInterface;
use App\Models\Photo;

class PhotoService extends Service implements PhotoServiceInterface
{
    public function __construct()
    {
        parent::__construct(Photo::class);
    }
}
