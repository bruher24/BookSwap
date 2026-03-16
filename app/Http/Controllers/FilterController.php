<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FilterResource;
use App\Interfaces\FilterServiceInterface;

final class FilterController extends CrudController
{
    public function __construct(FilterServiceInterface $filterService)
    {
        $this->resourceClass = FilterResource::class;
        $this->resourceKey = 'filter';
        $this->resourceCollectionKey = 'filters';
        $this->storeRequestClass = StoreFilterRequest::class;
        $this->updateRequestClass = UpdateFilterRequest::class;
        $this->createErrorMessage = 'Ошибка при создании фильтра';
        $this->getErrorMessage = 'Ошибка при получении фильтра';
        $this->updateErrorMessage = 'Ошибка при обновлении фильтра';
        $this->deleteErrorMessage = 'Ошибка при удалении фильтра';

        parent::__construct($filterService);
    }
}
