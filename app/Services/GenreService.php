<?php

namespace App\Services;

use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Override;

final class GenreService extends Service implements GenreServiceInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
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

    #[Override]
    public function update(string $id, array $data): Genre|false
    {
        return parent::update($id, $data);
    }
}
