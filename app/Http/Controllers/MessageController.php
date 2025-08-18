<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\MessageResource;
use App\Interfaces\MessageServiceInterface;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(MessageServiceInterface $messageService, Request $request): JsonResponse
    {
        $message = $messageService->create($request->all());
        $messageResource = new MessageResource($message);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при создании сообщения']);
        }
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно создано');
    }

    public function show(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $message = $messageService->get($id);
        $messageResource = new MessageResource($message);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при получении сообщения']);
        }
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно получено');
    }

    public function update(MessageServiceInterface $messageService, Request $request, string $id): JsonResponse
    {
        $updated = $messageService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении сообщения']);
        }
        return ResponseHelper::successResponse([], 'Сообщение успешно обновлено');
    }

    public function destroy(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $deleted = $messageService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении сообщения']);
        }
        return ResponseHelper::successResponse([], 'Сообщение успешно удалено');
    }

    // TODO: такой же метод есть в ChatController
    public function sendMessage(
        Request $request,
        UserServiceInterface $userService,
        string $user_id,
        string $recipient_id
    ): JsonResponse {
        $body = $request->input('body');
        $message = $userService->sendMessage($user, $recipient, $body);
        $messageResource = new MessageResource($message);
        if (!$message) {
            return ResponseHelper::errorResponse(['Ошибка при отправке сообщения']);
        }
        return ResponseHelper::successResponse([
            'message' => $messageResource,
        ], 'Сообщение успешно отправлено');
    }
}
