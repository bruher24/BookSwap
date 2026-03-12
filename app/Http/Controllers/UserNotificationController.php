<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailureResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\NotificationServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserNotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService, string $user_id): JsonResponse
    {
        $notifications = $notificationService->byUser($user_id);
        $notificationResourceCollection = NotificationResource::collection($notifications);
        $data = ['notifications' => $notificationResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function readAll(NotificationServiceInterface $notificationService, string $user_id): JsonResponse
    {
        if (!$notificationService->readAll($user_id)) {
            $errors = ['Ошибка при обновлении уведомлений'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
