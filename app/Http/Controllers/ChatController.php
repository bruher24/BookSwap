<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function index(ChatServiceInterface $chatService): JsonResponse
    {
        $chats = $chatService->getAll();
        return ResponseHelper::successResponse('Success', [
            'chats' => $chats,
        ]);
    }

    public function store(ChatServiceInterface $chatService, Request $request): JsonResponse
    {
        $chat = $chatService->create($request->all());
        if (!$chat) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании чата',
            ]);
        }
        return ResponseHelper::successResponse('Чат успешно создан', [
            'chat' => $chat,
        ]);
    }

    public function show(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $chat = $chatService->get($id);
        if (!$chat) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении чата',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'chat' => $chat,
        ]);
    }

    public function update(ChatServiceInterface $chatService, Request $request, string $id): JsonResponse
    {
        $updated = $chatService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении чата',
            ]);
        }
        return ResponseHelper::successResponse('Чат успешно обновлен');
    }

    public function destroy(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $deleted = $chatService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении чата',
            ]);
        }
        return ResponseHelper::successResponse('Чат успешно удален');
    }

    public function messages(UserServiceInterface $userService, User $user, User $recipient): JsonResponse
    {
        // TODO: вынести в сервис
        $chat = $userService->getChat($user, $recipient);
        $grouped = $userService->groupMessages($chat->messages()->orderBy('created_at')->orderBy('id')->get());

        return ResponseHelper::successResponse('Success', [
            'messages' => $grouped,
            'recipient' => $recipient,
            'is_blocked' => $chat->is_blocked,
        ]);
    }

    public function sendMessage()
    {
    }
}
