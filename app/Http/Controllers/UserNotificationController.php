<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;

class UserNotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService, string $user_id): JsonResponse
    {
        $notifications = $notificationService->byUser($user_id);
        $notificationResourceCollection = NotificationResource::collection($notifications);
        return ResponseHelper::successResponse([
            'notifications' => $notificationResourceCollection,
        ]);
    }

    public function readAll(NotificationServiceInterface $notificationService, string $user_id): JsonResponse
    {
        if (!$notificationService->readAll($user_id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении уведомлений']);
        }
        return ResponseHelper::successResponse([], 'Уведомления успешно прочитаны');
    }
}
