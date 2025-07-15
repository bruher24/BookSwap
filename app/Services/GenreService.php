<?php

namespace App\Services;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

final class GenreService extends Service
{
    public function __construct() {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function books(array $filters = []): array
    {
        $books = $this->bookService->where($filters);
        $allBooks = $this->bookService->where(['genre' => [$filters['genre']]]);
        $params = $this->params($allBooks);
        return [$books, $params];
    }

    public function params($books): array
    {
        $params = parent::params($books);
        unset($params['genres']);
        return $params;
    }

    public function where(array $conditions): Collection|false
    {
        return Genre::whereIn('id', $conditions['genre'])->get() ?? false;
    }
}
