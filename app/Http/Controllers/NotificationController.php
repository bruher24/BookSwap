<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService): JsonResponse
    {
        $notifications = $notificationService->getAll();
        $notificationResourceCollection = NotificationResource::collection($notifications);
        return ResponseHelper::successResponse([
            'notifications' => $notificationResourceCollection,
        ]);
    }

    public function store(
        NotificationServiceInterface $notificationService,
        StoreNotificationRequest $request
    ): JsonResponse {
        $validated = $request->validated();
        $notification = $notificationService->create($validated);
        if (!$notification) {
            return ResponseHelper::errorResponse(['Ошибка при создании уведомления']);
        }
        $notificationResource = new NotificationResource($notification);
        return ResponseHelper::successResponse([
            'notification' => $notificationResource,
        ], 'Уведомление успешно создано');
    }

    public function show(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $notification = $notificationService->get($id);
        if (!$notification) {
            return ResponseHelper::errorResponse(['Ошибка при получении уведомления']);
        }
        $notificationResource = new NotificationResource($notification);
        return ResponseHelper::successResponse([
            'notification' => $notificationResource,
        ]);
    }

    public function update(
        NotificationServiceInterface $notificationService,
        UpdateNotificationRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$notificationService->update($id, $validated)) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении уведомления']);
        }
        return ResponseHelper::successResponse([], 'Уведомление успешно обновлено');
    }

    public function destroy(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        if (!$notificationService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении уведомления']);
        }
        return ResponseHelper::successResponse([], 'Уведомление успешно удалено');
    }
}
