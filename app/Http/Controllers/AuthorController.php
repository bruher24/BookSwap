<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Interfaces\AuthorServiceInterface;

final class AuthorController extends CrudController
{
    public function __construct(AuthorServiceInterface $authorService)
    {
        $this->resourceClass = AuthorResource::class;
        $this->resourceKey = 'author';
        $this->resourceCollectionKey = 'authors';
        $this->storeRequestClass = StoreAuthorRequest::class;
        $this->updateRequestClass = UpdateAuthorRequest::class;
        $this->createErrorMessage = 'Ошибка при создании автора';
        $this->getErrorMessage = 'Ошибка при получении автора';
        $this->updateErrorMessage = 'Ошибка при обновлении автора';
        $this->deleteErrorMessage = 'Ошибка при удалении автора';

        parent::__construct($authorService);
    }
}
