<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;

class GenreService extends Service implements GenreServiceInterface
{
    public function __construct()
    {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function create(array $data): Genre|false
    {
        return parent::create($data);
    }

    public function get(int $id): Genre|false
    {
        return parent::get($id);
    }
}
