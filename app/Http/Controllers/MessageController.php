<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\MessageServiceInterface;
use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(MessageServiceInterface $messageService): JsonResponse
    {
        $messages = $messageService->getAll();
        return ResponseHelper::successResponse('Success', [
            'messages' => $messages,
        ]);
    }

    public function create(MessageServiceInterface $messageService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(MessageServiceInterface $messageService, Request $request): JsonResponse
    {
        $message = $messageService->create($request->all());
        if (!$message) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Сообщение успешно создано', [
            'message' => $message,
        ]);
    }

    public function show(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $message = $messageService->get($id);
        if (!$message) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Сообщение успешно получено', [
            'message' => $message,
        ]);
    }

    public function edit(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function update(MessageServiceInterface $messageService, Request $request, string $id): JsonResponse
    {
        $updated = $messageService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Сообщение успешно обновлено');
    }

    public function destroy(MessageServiceInterface $messageService, string $id): JsonResponse
    {
        $deleted = $messageService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Сообщение успешно удалено');
    }

    public function sendMessage(
        Request $request,
        UserServiceInterface $userService,
        User $user,
        User $recipient
    ): JsonResponse {
        $body = $request->input('body');
        $message = $userService->sendMessage($user, $recipient, $body);
        if (!$message) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при отправке сообщения',
            ]);
        }
        return ResponseHelper::successResponse('Сообщение успешно отправлено', [
            'message' => $message,
        ]);
    }
}
