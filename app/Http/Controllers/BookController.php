<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Interfaces\BookServiceInterface;

final class BookController extends CrudController
{
    public function __construct(BookServiceInterface $bookService)
    {
        $this->resourceClass = BookResource::class;
        $this->resourceKey = 'book';
        $this->resourceCollectionKey = 'books';
        $this->storeRequestClass = StoreBookRequest::class;
        $this->updateRequestClass = UpdateBookRequest::class;
        $this->createErrorMessage = 'Ошибка при создании книги';
        $this->getErrorMessage = 'Ошибка при получении книги';
        $this->updateErrorMessage = 'Ошибка при обновлении книги';
        $this->deleteErrorMessage = 'Ошибка при удалении книги';

        parent::__construct($bookService);
    }
}
