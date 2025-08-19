<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Interfaces\MessageServiceInterface;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    public function index(MessageServiceInterface $messageService): JsonResponse
    {
        $messages = $messageService->getAll();
        $messageResourceCollection = MessageResource::collection($messages);
        return ResponseHelper::successResponse([
            'messages' => $messageResourceCollection,
        ]);
    }

    public function store(MessageServiceInterface $messageService, StoreMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $message = $messageService->create($validated);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при создании сообщения']);
        }
        $messageResource = new MessageResource($message);
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно создано');
    }

    public function show(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $message = $messageService->get($id);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при получении сообщения']);
        }
        $messageResource = new MessageResource($message);
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно получено');
    }

    public function update(
        MessageServiceInterface $messageService,
        UpdateMessageRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$messageService->update($id, $validated)) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении сообщения']);
        }
        return ResponseHelper::successResponse([], 'Сообщение успешно обновлено');
    }

    public function destroy(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        if (!$messageService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении сообщения']);
        }
        return ResponseHelper::successResponse([], 'Сообщение успешно удалено');
    }
}
