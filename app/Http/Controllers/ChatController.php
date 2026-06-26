<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\MessageResource;
use App\Interfaces\ChatServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class ChatController extends Controller
{
    public function store(ChatServiceInterface $chatService, StoreChatRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Gate::authorize('create', [Chat::class, $validated]);

        $chat = $chatService->create($validated);

        if (!$chat) {
            return $this->errorResponse('Ошибка при создании чата', Response::HTTP_BAD_REQUEST);
        }

        return (new ChatResource($chat))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Chat $chat): JsonResponse
    {
        Gate::authorize('view', $chat);

        return (new ChatResource($chat))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(ChatServiceInterface $chatService, int $chatId): JsonResponse
    {
        $chat = $chatService->get($chatId);

        if (!$chat) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $chat);

        if (!$chatService->delete($chat)) {
            return $this->errorResponse('Ошибка при удалении чата', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }

    public function byUser(ChatServiceInterface $chatService, User $user): JsonResponse
    {
        Gate::authorize('chats', $user);

        $chats = $chatService->byUser($user);

        return ChatResource::collection($chats)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function messages(ChatServiceInterface $chatService, Chat $chat): JsonResponse
    {
        Gate::authorize('messages', $chat);

        $messages = $chatService->messages($chat);

        return MessageResource::collection($messages)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function send(UserServiceInterface $userService, ChatServiceInterface $chatService, SendMessageRequest $request, Chat $chat): JsonResponse
    {
        Gate::authorize('send', [$chat, $request->input('sender_id')]);

        $validated = $request->validated();
        $sender = $userService->get((int)$validated['sender_id']);

        if (!$sender) {
            return $this->errorResponse('Отправитель не найден', Response::HTTP_NOT_FOUND);
        }

        $message = $chatService->sendMessage($chat, $sender, $validated['body']);

        if (!$message) {
            return $this->errorResponse('Ошибка при отправке сообщения', Response::HTTP_BAD_REQUEST);
        }

        return (new MessageResource($message))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @psalm-suppress PossiblyNullArgument
     */
    public function block(Request $request, ChatServiceInterface $chatService, Chat $chat): JsonResponse
    {
        Gate::authorize('block', $chat);

        if (!$chatService->block($chat, $request->user())) {
            return $this->errorResponse('Ошибка при блокировке чата', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
