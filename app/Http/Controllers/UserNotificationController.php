<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
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

        return NotificationResource::collection($notifications)->response()->setStatusCode(Response::HTTP_OK);
    }
}
