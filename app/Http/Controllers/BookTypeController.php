<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Interfaces\BookTypeServiceInterface;

final class BookTypeController extends CrudController
{
    public function __construct(BookTypeServiceInterface $bookTypeService)
    {
        $this->resourceClass = BookTypeResource::class;
        $this->resourceKey = 'bookType';
        $this->resourceCollectionKey = 'bookTypes';
        $this->storeRequestClass = StoreBookTypeRequest::class;
        $this->updateRequestClass = UpdateBookTypeRequest::class;
        $this->createErrorMessage = 'Ошибка при создании типа';
        $this->getErrorMessage = 'Ошибка при получении типа';
        $this->updateErrorMessage = 'Ошибка при обновлении типа';
        $this->deleteErrorMessage = 'Ошибка при удалении типа';

        parent::__construct($bookTypeService);
    }
}
