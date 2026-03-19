<?php

namespace App\Http\Controllers;

use App\Http\Resources\FailureResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\NotificationServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UserNotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService, User $user): JsonResponse
    {
        Gate::authorize('notifications', $user);

        $notifications = $notificationService->byUser($user);
        $data = ['notifications' => NotificationResource::collection($notifications)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function readAll(NotificationServiceInterface $notificationService, User $user): JsonResponse
    {
        Gate::authorize('notifications', $user);

        if (!$notificationService->readAll($user)) {
            $errors = ['Ошибка при обновлении уведомлений'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
