<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class NotificationController extends Controller
{
    public function index(NotificationServiceInterface $notificationService): JsonResponse
    {
        $notifications = $notificationService->getAll();

        return NotificationResource::collection($notifications)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(NotificationServiceInterface $notificationService, StoreNotificationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $notification = $notificationService->create($validated);

        if (!$notification) {
            return $this->errorResponse('Ошибка при создании уведомления', Response::HTTP_BAD_REQUEST);
        }

        return (new NotificationResource($notification))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Notification $notification): JsonResponse
    {
        return (new NotificationResource($notification))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(NotificationServiceInterface $notificationService, UpdateNotificationRequest $request, Notification $notification): JsonResponse
    {
        $validated = $request->validated();
        $notification = $notificationService->update($notification, $validated);

        if (!$notification) {
            return $this->errorResponse('Ошибка при обновлении уведомления', Response::HTTP_BAD_REQUEST);
        }

        return (new NotificationResource($notification))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(NotificationServiceInterface $notificationService, int $notificationId): JsonResponse
    {
        $notification = $notificationService->get($notificationId);

        if (!$notification) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        if (!$notificationService->delete($notification)) {
            return $this->errorResponse('Ошибка при удалении уведомления', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
    public function byUser(NotificationServiceInterface $notificationService, User $user): JsonResponse
    {
        Gate::authorize('notifications', $user);

        $notifications = $notificationService->byUser($user);

        return NotificationResource::collection($notifications)->response()->setStatusCode(Response::HTTP_OK);
    }
}

// TODO: read, StoreNotificationRequest, UpdateNotificationRequest
