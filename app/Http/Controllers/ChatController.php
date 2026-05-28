<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\MessageCollectionResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SuccessResource;
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
            $errors = ['Ошибка при создании чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['chat' => new ChatResource($chat)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Chat $chat): JsonResponse
    {
        Gate::authorize('view', $chat);

        $data = ['chat' => new ChatResource($chat)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(ChatServiceInterface $chatService, string $chatId): JsonResponse
    {
        $chat = $chatService->get($chatId);

        if (!$chat) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $chat);

        if (!$chatService->delete($chat)) {
            $errors = ['Ошибка при удалении чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function byUser(ChatServiceInterface $chatService, User $user): JsonResponse
    {
        Gate::authorize('chats', $user);

        $chats = $chatService->byUser($user);
        $data = ['chats' => ChatResource::collection($chats)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function messages(ChatServiceInterface $chatService, Chat $chat): JsonResponse
    {
        Gate::authorize('messages', $chat);

        $messages = $chatService->messages($chat);
        $data = ['messages' => new MessageCollectionResource($messages)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function send(UserServiceInterface $userService, ChatServiceInterface $chatService, SendMessageRequest $request, Chat $chat): JsonResponse
    {
        Gate::authorize('send', [$chat, $request->input('sender_id')]);

        $validated = $request->validated();
        $sender = $userService->get($validated['sender_id']);

        if (!$sender) {
            $errors = ['Отправитель не найден'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $message = $chatService->sendMessage($chat, $sender, $validated['body']);

        if (!$message) {
            $errors = ['Ошибка при отправке сообщения'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['message' => new MessageResource($message)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function block(Request $request, ChatServiceInterface $chatService, Chat $chat): JsonResponse
    {
        Gate::authorize('block', $chat);

        if (!$chatService->block($chat, $request->user())) {
            $errors = ['Ошибка при блокировке чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
