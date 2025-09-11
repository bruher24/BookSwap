<?php

namespace App\Services;

use App\Interfaces\BookTypeServiceInterface;
use App\Models\BookType;

class BookTypeService extends Service implements BookTypeServiceInterface
{
    public function __construct()
    {
        parent::__construct(BookType::class);
    }

    public function create(array $data): BookType|false
    {
        return parent::create($data);
    }

    public function get(string $id): BookType|false
    {
        return parent::get($id);
    }
}
