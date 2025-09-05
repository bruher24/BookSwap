<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\SettingResource;
use App\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    public function index(UserServiceInterface $userService, string $user_id): JsonResponse
    {
        $user = $userService->get($user_id);
        $settingResourceCollection = SettingResource::collection($user->settings()->get());
        return ResponseHelper::successResponse([
            'settings' => $settingResourceCollection,
        ]);
    }

    public function update(
        UserServiceInterface $userService,
        UpdateSettingValueRequest $request,
        string $user_id,
        string $setting_id
    ): JsonResponse {
        $user = $userService->get($user_id);
        $updated = $user->settings()->updateExistingPivot($setting_id, [
            'value' => $request->input('value'),
        ]);
        if (!$updated) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении настройки']);
        }
        return ResponseHelper::successResponse([], 'Настройка успешно обновлена');
    }
}
