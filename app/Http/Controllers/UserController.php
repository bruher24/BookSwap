<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\BookServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index(UserServiceInterface $userService): JsonResponse
    {
        $users = $userService->getAll();
        return ResponseHelper::successResponse('Success', [
            'users' => $users,
        ]);
    }

    public function store(UserServiceInterface $userService, StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = $userService->create($validated);
        if (!$user) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании пользователя',
            ]);
        }
        return ResponseHelper::successResponse('Пользователь успешно создан', [
            'user' => $user,
        ]);
    }

    public function show(UserServiceInterface $userService, string $id): JsonResponse
    {
        $user = $userService->get($id);
        if (!$user) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении пользователя',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'user' => $user,
        ]);
    }

    public function update(UserServiceInterface $userService, UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = $userService->get($id);
        if (!Gate::allows('crud-itself', $user)) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка доступа'
            ]);
        }

        $validated = $request->validated();
        $updated = $userService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении пользователя'
            ]);
        }

        return ResponseHelper::successResponse('Пользователь успешно обновлен');
    }

    public function destroy(UserServiceInterface $userService, string $id): JsonResponse
    {
        $deleted = $userService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении пользователя',
            ]);
        }
        return ResponseHelper::successResponse('Пользователь успешно удален');
    }

    public function chats(UserServiceInterface $userService, Request $request, User $user): JsonResponse
    {
        // TODO: сделать нормально
        $recipient = $request->input('recipient');
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

        return ResponseHelper::successResponse('Success', [
            'chats' => $chats,
            'recipient' => $recipient,
        ]);
    }

    public function messages(UserServiceInterface $userService, User $user): JsonResponse
    {
        $messages = $userService->getUnreadMessages($user);
        return ResponseHelper::successResponse('Success', [
            'messages' => $messages,
        ]);
    }

    public function read(UserServiceInterface $userService, Request $request, User $user): JsonResponse
    {
        // TODO: сделать нормально
        $messagesToRead = $request->input('messages');
        $checked = $userService->readMessages($user, $messagesToRead);
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении сообщений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }


    // TODO: убрать отсюда или добавить роут
    public function books(BookServiceInterface $bookService, Request $request, User $user): JsonResponse
    {
        // TODO: сделать нормально
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byUser($user, $filters);

        return ResponseHelper::successResponse('Success', [
            'books' => $books,
            'params' => $params,
            'filters' => $filters,
        ]);
    }
}
