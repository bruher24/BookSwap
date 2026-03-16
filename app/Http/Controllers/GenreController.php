<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use App\Interfaces\GenreServiceInterface;

final class GenreController extends CrudController
{
    public function __construct(GenreServiceInterface $genreService)
    {
        $this->resourceClass = GenreResource::class;
        $this->resourceKey = 'genre';
        $this->resourceCollectionKey = 'genres';
        $this->storeRequestClass = StoreGenreRequest::class;
        $this->updateRequestClass = UpdateGenreRequest::class;
        $this->createErrorMessage = 'Ошибка при создании жанра';
        $this->getErrorMessage = 'Ошибка при получении жанра';
        $this->updateErrorMessage = 'Ошибка при обновлении жанра';
        $this->deleteErrorMessage = 'Ошибка при удалении жанра';

        parent::__construct($genreService);
    }
}
