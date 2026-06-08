<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function index(UserServiceInterface $userService): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $users = $userService->getAll();
        $data = ['users' => UserResource::collection($users)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(UserServiceInterface $userService, StoreUserRequest $request): JsonResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            $errors = ['Ошибка при создании пользователя'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['user' => new UserResource($user)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('view', $user);

        $data = ['user' => new UserResource($user)];
        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UserServiceInterface $userService, UpdateUserRequest $request, User $user): JsonResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();
        $user = $userService->update($user, $validated);

        if (!$user) {
            $errors = ['Ошибка при обновлении пользователя'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['user' => new UserResource($user)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(UserServiceInterface $userService, string $userId): JsonResponse
    {
        $user = $userService->get($userId);

        if (!$user) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $user);

        if (!$userService->delete($user)) {
            $errors = ['Ошибка при удалении пользователя'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function rate(RateUserRequest $request, UserServiceInterface $userService, User $user): JsonResponse
    {
        Gate::authorize('rate', $user);

        $validated = $request->validated();

        if ($userService->rate($user, $request->user(), (int)$validated['rate'])) {
            $errors = ['Ошибка при оценке пользователя'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
