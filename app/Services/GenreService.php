<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Override;

class GenreService extends Service implements GenreServiceInterface
{
    public function __construct()
    {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    #[Override]
    public function create(array $data): Genre|false
    {
        return parent::create($data);
    }

    #[Override]
    public function get(string $id): Genre|false
    {
        return parent::get($id);
    }
}
