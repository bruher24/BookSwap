<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\ChatServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class ChatController extends Controller
{
    public function index(ChatServiceInterface $chatService): JsonResponse
    {
        $chats = $chatService->getAll();
        $chatResourceCollection = ChatResource::collection($chats);
        $data = ['chats' => $chatResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(ChatServiceInterface $chatService, StoreChatRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $chat = $chatService->create($validated);

        if (!$chat) {
            $errors = ['Ошибка при создании чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $chatResource = new ChatResource($chat);
        $data = ['chat' => $chatResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        $chat = $chatService->get($id);

        if (!$chat) {
            $errors = ['Ошибка при получении чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $chatResource = new ChatResource($chat);
        $data = ['chat' => $chatResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(ChatServiceInterface $chatService, UpdateChatRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $chat = $chatService->update($id, $validated);

        if (!$chat) {
            $errors = ['Ошибка при обновлении чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $chatResource = new ChatResource($chat);
        $data = ['chat' => $chatResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(ChatServiceInterface $chatService, string $id): JsonResponse
    {
        if (!$chatService->delete($id)) {
            $errors = ['Ошибка при удалении чата'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function byUser(ChatServiceInterface $chatService, string $userId): JsonResponse
    {
        $chats = $chatService->byUser($userId);
        $chatResourceCollection = ChatResource::collection($chats);
        $data = ['chats' => $chatResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

     public function messages(ChatServiceInterface $chatService, string $chatId): JsonResponse
     {
         $messages = $chatService->messages($chatId);
         $messageResourceCollection = MessageResource::collection($messages);
         $data = ['messages' => $messageResourceCollection];

         return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
     }

     public function send(ChatServiceInterface $chatService, SendMessageRequest $request, string $chatId): JsonResponse
     {
         $validated = $request->validated();
         $message = $chatService->sendMessage($chatId, $validated['sender_id'], $validated['body']);

         if (!$message) {
             $errors = ['Ошибка при отправке сообщения'];
             return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
         }

         $messageResource = new MessageResource($message);
         $data = ['message' => $messageResource];

         return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
     }
}
