<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\UserResource;
use App\Interfaces\UserServiceInterface;
use App\Models\Book;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UserController extends Controller
{
    public function index(UserServiceInterface $userService): JsonResponse
    {
        $users = $userService->getAll();

        return UserResource::collection($users)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(UserServiceInterface $userService, StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $userService->create($validated);

        if (!$user) {
            return $this->errorResponse('Ошибка при создании пользователя', Response::HTTP_BAD_REQUEST);
        }

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('view', $user);

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UserServiceInterface $userService, UpdateUserRequest $request, User $user): JsonResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();
        $user = $userService->update($user, $validated);

        if (!$user) {
            return $this->errorResponse('Ошибка при обновлении пользователя', Response::HTTP_BAD_REQUEST);
        }

        return (new UserResource($user))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(UserServiceInterface $userService, int $userId): JsonResponse
    {
        $user = $userService->get($userId);

        if (!$user) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $user);

        if (!$userService->delete($user)) {
            return $this->errorResponse('Ошибка при удалении пользователя', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function rate(RateUserRequest $request, UserServiceInterface $userService, User $user): JsonResponse
    {
        Gate::authorize('rate', $user);

        $validated = $request->validated();

        if (!$userService->rate($user, $request->user(), (int)$validated['rate'])) {
            return $this->errorResponse('Ошибка при оценке пользователя', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

    public function favorites(UserService $userService, User $user): JsonResponse
    {
        Gate::authorize('favorites', $user);

        $favorites = $userService->favorites($user);

        return BookResource::collection($favorites)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function like(UserService $userService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userService->addToFavorites($user, $book)) {
            return $this->errorResponse('Ошибка добавления книги в избранное', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

    public function dislike(UserService $userService, User $user, Book $book): JsonResponse
    {
        Gate::authorize('favorites', $user);

        if (!$userService->removeFromFavorites($user, $book)) {
            return $this->errorResponse('Ошибка удаления книги из избранного', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
