<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;

final class UserController extends CrudController
{
    public function __construct(UserServiceInterface $userService)
    {
        $this->resourceClass = UserResource::class;
        $this->resourceKey = 'user';
        $this->resourceCollectionKey = 'users';
        $this->storeRequestClass = StoreUserRequest::class;
        $this->updateRequestClass = UpdateUserRequest::class;
        $this->createErrorMessage = 'Ошибка при создании пользователя';
        $this->getErrorMessage = 'Ошибка при получении пользователя';
        $this->updateErrorMessage = 'Ошибка при обновлении пользователя';
        $this->deleteErrorMessage = 'Ошибка при удалении пользователя';

        parent::__construct($userService);
    }
}
