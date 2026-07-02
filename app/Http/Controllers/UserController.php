<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\ChatResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\TradeOfferHistoryResource;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthorServiceInterface;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\NotificationServiceInterface;
use App\Interfaces\SettingServiceInterface;
use App\Interfaces\TradeOfferServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Book;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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

    public function me(): JsonResponse
    {
        return (new UserResource(Auth::user()))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function favorites(UserService $userService): JsonResponse
    {
        $favorites = $userService->favorites(request()->user());

        return BookResource::collection($favorites)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function like(UserService $userService, Book $book): JsonResponse
    {
        if (!$userService->addToFavorites(request()->user(), $book)) {
            return $this->errorResponse('Ошибка добавления книги в избранное', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse();
    }

    public function dislike(UserService $userService, Book $book): JsonResponse
    {
        if (!$userService->removeFromFavorites(request()->user(), $book)) {
            return $this->errorResponse('Ошибка удаления книги из избранного', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }

    public function authors(AuthorServiceInterface $authorService): JsonResponse
    {
        $authors = $authorService->byUser(request()->user());

        return AuthorResource::collection($authors)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function books(BookServiceInterface $bookService): JsonResponse
    {
        $books = $bookService->byUser(request()->user());

        return BookResource::collection($books)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function chats(ChatServiceInterface $chatService): JsonResponse
    {
        $chats = $chatService->byUser(request()->user());

        return ChatResource::collection($chats)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function notifications(NotificationServiceInterface $notificationService): JsonResponse
    {
        $notifications = $notificationService->byUser(request()->user());

        return NotificationResource::collection($notifications)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function settings(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->byUser(request()->user());

        return SettingResource::collection($settings)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function tradeHistory(TradeOfferServiceInterface $tradeOfferService): JsonResponse
    {
        $history = $tradeOfferService->tradeHistory(request()->user());

        return (new TradeOfferHistoryResource($history))->response()->setStatusCode(Response::HTTP_OK);
    }
}
