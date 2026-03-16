<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Interfaces\PhotoServiceInterface;

final class PhotoController extends CrudController
{
    public function __construct(PhotoServiceInterface $photoService)
    {
        $this->resourceClass = PhotoResource::class;
        $this->resourceKey = 'photo';
        $this->resourceCollectionKey = 'photos';
        $this->storeRequestClass = StorePhotoRequest::class;
        $this->updateRequestClass = UpdatePhotoRequest::class;
        $this->createErrorMessage = 'Ошибка при создании фото';
        $this->getErrorMessage = 'Ошибка при получении фото';
        $this->updateErrorMessage = 'Ошибка при обновлении фото';
        $this->deleteErrorMessage = 'Ошибка при удалении фото';

        parent::__construct($photoService);
    }
}
