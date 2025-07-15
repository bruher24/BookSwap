<?php

namespace App\Services;

use App\Models\BookType;

class BookTypeService extends Service
{
    public function __construct()
    {
        parent::__construct(BookType::class);
        $this->ucFirstFields = [
            'name',
        ];
    }
}