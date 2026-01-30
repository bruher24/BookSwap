<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailureResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserNotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService, string $user_id): JsonResource
    {
        $notifications = $notificationService->byUser($user_id);
        $notificationResourceCollection = NotificationResource::collection($notifications);
        $data = ['notifications' => $notificationResourceCollection];

        return new SuccessResource($data);
    }

    public function readAll(NotificationServiceInterface $notificationService, string $user_id): JsonResource
    {
        if (!$notificationService->readAll($user_id)) {
            $errors = ['Ошибка при обновлении уведомлений'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
