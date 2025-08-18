<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(NotificationServiceInterface $notificationService, Request $request): JsonResponse
    {
        $notification = $notificationService->create($request->all());
        $notificationResource = new NotificationResource($notification);
        if (!$notification) {
            return ResponseHelper::errorResponse(['Ошибка при создании уведомления']);
        }
        return ResponseHelper::successResponse([
            'notification' => $notificationResource,
        ], 'Уведомление успешно создано');
    }

    public function show(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $notification = $notificationService->get($id);
        $notificationResource = new NotificationResource($notification);
        if (!$notification) {
            return ResponseHelper::errorResponse(['Ошибка при получении уведомления']);
        }
        return ResponseHelper::successResponse([
            'notification' => $notificationResource,
        ]);
    }

    public function update(
        NotificationServiceInterface $notificationService,
        Request $request,
        string $id
    ): JsonResponse {
        $updated = $notificationService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении уведомления']);
        }
        return ResponseHelper::successResponse([], 'Уведомление успешно обновлено');
    }

    public function destroy(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $deleted = $notificationService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении уведомления']);
        }
        return ResponseHelper::successResponse([], 'Уведомление успешно удалено');
    }
}
