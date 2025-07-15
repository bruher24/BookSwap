<?php

namespace App\Services;

use App\Interfaces\BookTypeServiceInterface;
use App\Models\BookType;

class BookTypeService extends Service implements BookTypeServiceInterface
{
    public function __construct()
    {
        parent::__construct(BookType::class);
        $this->ucFirstFields = [
            'name',
        ];
    }
}