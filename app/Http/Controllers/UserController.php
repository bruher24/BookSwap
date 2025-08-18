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
        $userResource = new UserResource($user);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при создании пользователя']);
        }
        return ResponseHelper::successResponse([
            'user' => $userResource,
        ], 'Пользователь успешно создан');
    }

    public function show(UserServiceInterface $userService, string $id): JsonResponse
    {
        $user = $userService->get($id);
        $userResource = new UserResource($user);
        if (!$user) {
            return ResponseHelper::errorResponse(['Ошибка при получении пользователя']);
        }
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
        $updated = $userService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении пользователя']);
        }

        return ResponseHelper::successResponse([], 'Пользователь успешно обновлен');
    }

    public function destroy(UserServiceInterface $userService, string $id): JsonResponse
    {
        $deleted = $userService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении пользователя']);
        }
        return ResponseHelper::successResponse([], 'Пользователь успешно удален');
    }

    public function chats(UserServiceInterface $userService, Request $request, string $id): JsonResponse
    {
        // TODO: сделать нормально
        $recipient = $request->input('recipient');
        $user = $userService->get($id);
        $chats = $userService->getUserChats($user);

        if (isset($recipient) && $chats->where('first_user_id', $recipient)->isEmpty()
            && $chats->where('second_user_id', $recipient)->isEmpty()) {
            $arr = [$recipient, $user->id];
            sort($arr);
            $user->chats()->create([
                'first_user_id' => $arr[0],
                'second_user_id' => $arr[1],
            ]);
            $user->refresh();
            $chats = $userService->getUserChats($user);
        }

        return ResponseHelper::successResponse([
            'chats' => $chats,
            'recipient' => $recipient,
        ]);
    }

    public function messages(UserServiceInterface $userService, string $id): JsonResponse
    {
        $user = $userService->get($id);
        $messages = $userService->getUnreadMessages($user);
        return ResponseHelper::successResponse([
            'messages' => $messages,
        ]);
    }

    public function read(UserServiceInterface $userService, Request $request, string $id): JsonResponse
    {
        // TODO: сделать нормально
        $messagesToRead = $request->input('messages');
        $user = $userService->get($id);
        $checked = $userService->readMessages($user, $messagesToRead);
        if (!$checked) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении сообщений']);
        }
        return ResponseHelper::successResponse();
    }


    // TODO: убрать отсюда или добавить роут
    public function books(
        UserServiceInterface $userService,
        BookServiceInterface $bookService,
        Request $request,
        string $id
    ): JsonResponse {
        // TODO: сделать нормально
        $user = $userService->get($id);
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byUser($user, $filters);

        return ResponseHelper::successResponse([
            'books' => $books,
            'params' => $params,
            'filters' => $filters,
        ]);
    }
}
