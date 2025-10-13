<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Interfaces\ChatServiceInterface;
use Illuminate\Http\JsonResponse;

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

    public function store(ChatServiceInterface $chatService, StoreChatRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $chat = $chatService->create($validated);
        if (!$chat) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании чата']);
        }
        $chatResource = new ChatResource($chat);

        return ResponseHelper::successResponse([
            'chat' => $chatResource,
        ], 'Чат успешно создан');
    }

    public function show(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $chat = $chatService->get($id);
        if (!$chat) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении чата']);
        }
        $chatResource = new ChatResource($chat);

        return ResponseHelper::successResponse([
            'chat' => $chatResource,
        ]);
    }

    public function update(ChatServiceInterface $chatService, UpdateChatRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        if (!$chatService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении чата']);
        }

        return ResponseHelper::successResponse([], 'Чат успешно обновлен');
    }

    public function destroy(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        if (!$chatService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении чата']);
        }

        return ResponseHelper::successResponse([], 'Чат успешно удален');
    }
}
