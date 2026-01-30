<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserController extends Controller
{
    public function index(UserServiceInterface $userService): JsonResource
    {
        $users = $userService->getAll();
        $data = ['users' => UserResource::collection($users)];

        return new SuccessResource($data);
    }

    public function store(UserServiceInterface $userService, StoreUserRequest $request): JsonResource
    {
        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            $errors = ['Ошибка при создании пользователя'];
            return new FailureResource($errors);
        }

        $data = ['user' => new UserResource($user)];

        return new SuccessResource($data);
    }

    public function show(UserServiceInterface $userService, string $id): JsonResource
    {
        $user = $userService->get($id);

        if (!$user) {
            $errors = ['Ошибка при получении пользователя'];
            return new FailureResource($errors);
        }

        $data = ['user' => new UserResource($user)];

        return new SuccessResource($data);
    }

    public function update(UserServiceInterface $userService, UpdateUserRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$userService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении пользователя'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }

    public function destroy(UserServiceInterface $userService, string $id): JsonResource
    {
        if (!$userService->delete($id)) {
            $errors = ['Ошибка при удалении пользователя'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
