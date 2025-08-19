<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(UserServiceInterface $userService): JsonResponse
    {
        $users = $userService->getAll();
        $userResourceCollection = UserResource::collection($users);
        return ResponseHelper::successResponse([
            'users' => $userResourceCollection,
        ]);
    }

    public function store(UserServiceInterface $userService, StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $userService->create($validated);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при создании пользователя']);
        }
        $userResource = new UserResource($user);
        return ResponseHelper::successResponse([
            'user' => $userResource,
        ], 'Пользователь успешно создан');
    }

    public function show(UserServiceInterface $userService, string $id): JsonResponse
    {
        $user = $userService->get($id);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при получении пользователя']);
        }
        $userResource = new UserResource($user);
        return ResponseHelper::successResponse([
            'user' => $userResource,
        ]);
    }

    public function update(UserServiceInterface $userService, UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $userService->get($id);
        if (!Gate::allows('crud-itself', $user)) {
            return ResponseHelper::errorResponse(['Ошибка доступа']);
        }

        $validated = $request->validated();
        if (!$userService->update($id, $validated)) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении пользователя']);
        }
        return ResponseHelper::successResponse([], 'Пользователь успешно обновлен');
    }

    public function destroy(UserServiceInterface $userService, string $id): JsonResponse
    {
        if (!$userService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении пользователя']);
        }
        return ResponseHelper::successResponse([], 'Пользователь успешно удален');
    }
}
