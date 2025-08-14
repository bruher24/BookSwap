<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService): JsonResponse
    {
        $notifications = $notificationService->getAll();
        return ResponseHelper::successResponse('Success', [
            'notifications' => $notifications,
        ]);
    }

    public function create(NotificationServiceInterface $notificationService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(NotificationServiceInterface $notificationService, Request $request): JsonResponse
    {
        $notification = $notificationService->create($request->all());
        if (!$notification) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании уведомления',
            ]);
        }
        return ResponseHelper::successResponse('Уведомление успешно создано', [
            'notification' => $notification,
        ]);
    }

    public function show(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $notification = $notificationService->get($id);
        if (!$notification) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении уведомления',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'notification' => $notification,
        ]);
    }

    public function edit(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function update(
        NotificationServiceInterface $notificationService,
        Request $request,
        string $id
    ): JsonResponse {
        $updated = $notificationService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении уведомления',
            ]);
        }
        return ResponseHelper::successResponse('Уведомление успешно обновлено');
    }

    public function destroy(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $deleted = $notificationService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении уведомления',
            ]);
        }
        return ResponseHelper::successResponse('Уведомление успешно удалено');
    }
}
