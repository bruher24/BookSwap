<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Interfaces\SettingServiceInterface;

final class SettingController extends CrudController
{
    public function __construct(SettingServiceInterface $settingService)
    {
        $this->resourceClass = SettingResource::class;
        $this->resourceKey = 'setting';
        $this->resourceCollectionKey = 'settings';
        $this->storeRequestClass = StoreSettingRequest::class;
        $this->updateRequestClass = UpdateSettingRequest::class;
        $this->createErrorMessage = 'Ошибка при создании настройки';
        $this->getErrorMessage = 'Ошибка при получении настройки';
        $this->updateErrorMessage = 'Ошибка при обновлении настройки';
        $this->deleteErrorMessage = 'Ошибка при удалении настройки';

        parent::__construct($settingService);
    }
}
