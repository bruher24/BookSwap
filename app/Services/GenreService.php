<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

class GenreService extends Service implements GenreServiceInterface
{
    public function __construct()
    {
        parent::__construct(Genre::class);
        $this->ucFirstFields = [
            'name',
        ];
    }

    public function getAll(): Collection
    {
        return Genre::whereHas('books')->get();
    }
}
