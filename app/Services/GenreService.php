<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreService extends Service
{
    public function __construct()
    {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function params(Collection $books = null): array
    {
        $params = parent::params($books);
        unset($params['genres']);
        return $params;
    }
}
