<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\ChatServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class ChatController extends Controller
{
    public function index(ChatServiceInterface $chatService): JsonResource
    {
        $chats = $chatService->getAll();
        $chatResourceCollection = ChatResource::collection($chats);
        $data = ['chats' => $chatResourceCollection];

        return new SuccessResource($data);
    }

    public function store(ChatServiceInterface $chatService, StoreChatRequest $request): JsonResource
    {
        $validated = $request->validated();
        $chat = $chatService->create($validated);

        if (!$chat) {
            $errors = ['Ошибка при создании чата'];
            return new FailureResource($errors);
        }

        $chatResource = new ChatResource($chat);
        $data = ['chat' => $chatResource];

        return new SuccessResource($data);
    }

    public function show(ChatServiceInterface $chatService, string $id): JsonResource
    {
        $chat = $chatService->get($id);

        if (!$chat) {
            $errors = ['Ошибка при получении чата'];
            return new FailureResource($errors);
        }

        $chatResource = new ChatResource($chat);
        $data = ['chat' => $chatResource];

        return new SuccessResource($data);
    }

    public function update(ChatServiceInterface $chatService, UpdateChatRequest $request, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$chatService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении чата'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }

    public function destroy(ChatServiceInterface $chatService, string $id): JsonResource
    {
        if (!$chatService->delete($id)) {
            $errors = ['Ошибка при удалении чата'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
