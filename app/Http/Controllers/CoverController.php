<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoverRequest;
use App\Http\Requests\UpdateCoverRequest;
use App\Http\Resources\CoverResource;
use App\Interfaces\CoverServiceInterface;

final class CoverController extends CrudController
{
    public function __construct(CoverServiceInterface $coverService)
    {
        $this->resourceClass = CoverResource::class;
        $this->resourceKey = 'cover';
        $this->resourceCollectionKey = 'covers';
        $this->storeRequestClass = StoreCoverRequest::class;
        $this->updateRequestClass = UpdateCoverRequest::class;
        $this->createErrorMessage = 'Ошибка при создании обложки';
        $this->getErrorMessage = 'Ошибка при получении обложки';
        $this->updateErrorMessage = 'Ошибка при обновлении обложки';
        $this->deleteErrorMessage = 'Ошибка при удалении обложки';

        parent::__construct($coverService);
    }
}
