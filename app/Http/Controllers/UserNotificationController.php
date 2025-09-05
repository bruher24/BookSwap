<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    // TODO: сделать тут все нормально
    public function index(UserServiceInterface $userService, User $user): JsonResponse
    {
        $notifications = $userService->getUserNotifications($user);
        return ResponseHelper::successResponse('Success', [
            'notifications' => $notifications,
        ]);
    }

    public function checkAll(Request $request, UserServiceInterface $userService, User $user): JsonResponse
    {
        $notifications = $request->input('notifications');
        if (isset($notifications)) {
            $checked = $userService->checkManyNotifications($user, $notifications);
        } else {
            $checked = $userService->checkAllNotifications($user);
        }
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении уведомлений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    public function check(UserServiceInterface $userService, User $user, Notification $notification): JsonResponse
    {
        $checked = $userService->checkOneNotification($user, $notification->id);
        if (!$checked) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении уведомлений',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
