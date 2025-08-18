<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\ChatResource;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ChatController extends Controller
{
    public function index(ChatServiceInterface $chatService): JsonResponse
    {
        $chats = $chatService->getAll();
        $chatResourceCollection = ChatResource::collection($chats);
        return ResponseHelper::successResponse([
            'chats' => $chatResourceCollection,
        ]);
    }

    public function store(ChatServiceInterface $chatService, Request $request): JsonResponse
    {
        $chat = $chatService->create($request->all());
        $chatResource = new ChatResource($chat);
        if (!$chat) {
            return ResponseHelper::errorResponse(['Ошибка при создании чата']);
        }
        return ResponseHelper::successResponse([
            'chat' => $chatResource,
        ], 'Чат успешно создан');
    }

    public function show(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $chat = $chatService->get($id);
        $chatResource = new ChatResource($chat);
        if (!$chat) {
            return ResponseHelper::errorResponse(['Ошибка при получении чата']);
        }
        return ResponseHelper::successResponse([
            'chat' => $chatResource,
        ]);
    }

    public function update(ChatServiceInterface $chatService, Request $request, string $id): JsonResponse
    {
        $updated = $chatService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении чата']);
        }
        return ResponseHelper::successResponse([], 'Чат успешно обновлен');
    }

    public function destroy(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $deleted = $chatService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении чата']);
        }
        return ResponseHelper::successResponse([], 'Чат успешно удален');
    }

    public function messages(UserServiceInterface $userService, string $user_id, string $recipient_id): JsonResponse
    {
        // TODO: добавить JsonResource
        // TODO: вынести в сервис
        $chat = $userService->getChat($user, $recipient);
        $grouped = $userService->groupMessages($chat->messages()->orderBy('created_at')->orderBy('id')->get());

        return ResponseHelper::successResponse([
            'messages' => $grouped,
            'recipient' => $recipient,
            'is_blocked' => $chat->is_blocked,
        ]);
    }

    public function sendMessage()
    {
    }
}
