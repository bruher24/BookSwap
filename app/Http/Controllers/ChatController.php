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

final class ChatController extends CrudController
{
    public function __construct(ChatServiceInterface $chatService)
    {
        $this->resourceClass = ChatResource::class;
        $this->resourceKey = 'chat';
        $this->resourceCollectionKey = 'chats';
        $this->storeRequestClass = StoreChatRequest::class;
        $this->updateRequestClass = UpdateChatRequest::class;
        $this->createErrorMessage = 'Ошибка при создании чата';
        $this->getErrorMessage = 'Ошибка при получении чата';
        $this->updateErrorMessage = 'Ошибка при обновлении чата';
        $this->deleteErrorMessage = 'Ошибка при удалении чата';

        parent::__construct($chatService);
    }

    public function byUser(string $userId): JsonResponse
    {
        $chats = $this->service->byUser($userId);
        $chatResourceCollection = ChatResource::collection($chats);
        $data = ['chats' => $chatResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function messages(string $chatId): JsonResponse
    {
        $messages = $this->service->messages($chatId);
        $messageResourceCollection = MessageResource::collection($messages);
        $data = ['messages' => $messageResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function send(SendMessageRequest $request, string $chatId): JsonResponse
    {
        $validated = $request->validated();
        $message = $this->service->sendMessage($chatId, $validated['sender_id'], $validated['body']);

        if (!$message) {
            $errors = ['Ошибка при отправке сообщения'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $messageResource = new MessageResource($message);
        $data = ['message' => $messageResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
