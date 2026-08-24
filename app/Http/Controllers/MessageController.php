<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Interfaces\MessageServiceInterface;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class MessageController extends Controller
{
    public function index(MessageServiceInterface $messageService): JsonResponse
    {
        Gate::authorize('viewAny', Message::class);

        $messages = $messageService->getAll();

        return MessageResource::collection($messages)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(MessageServiceInterface $messageService, StoreMessageRequest $request): JsonResponse
    {
        Gate::authorize('create', Message::class);

        $validated = $request->validated();
        $message = $messageService->create($validated);

        if (!$message) {
            return $this->errorResponse('Ошибка при создании сообщения', Response::HTTP_BAD_REQUEST);
        }

        return (new MessageResource($message))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Message $message): JsonResponse
    {
        return (new MessageResource($message))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(MessageServiceInterface $messageService, UpdateMessageRequest $request, Message $message): JsonResponse
    {
        Gate::authorize('update', $message);

        $validated = $request->validated();
        $message = $messageService->update($message, $validated);

        if (!$message) {
            return $this->errorResponse('Ошибка при обновлении сообщения', Response::HTTP_BAD_REQUEST);
        }

        return (new MessageResource($message))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(MessageServiceInterface $messageService, int $messageId): JsonResponse
    {
        $message = $messageService->get($messageId);

        if (!$message) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $message);

        if (!$messageService->delete($message)) {
            return $this->errorResponse('Ошибка при удалении сообщения', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
