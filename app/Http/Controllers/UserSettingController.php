<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    public function index()
    {
    }

    public function add()
    {
    }

    public function update(UserServiceInterface $userService, Request $request, User $user): JsonResponse
    {
        $updated = $userService->updateSettings($user, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении настроек пользователя',
            ]);
        }
        return ResponseHelper::successResponse('Настройки пользователя успешно обновлены');
    }
}
