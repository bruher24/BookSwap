<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\UpdateSettingsValuesRequest;
use App\Http\Resources\SettingResource;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserSettingServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;

final class UserSettingController extends Controller
{
    public function index(UserServiceInterface $userService, string $user_id): JsonResponse
    {
        $user = $userService->get($user_id);
        if (!$user instanceof User) {
            return ResponseHelper::errorResponse(400, ['Пользователь не найден']);
        }
        $settingResourceCollection = SettingResource::collection($user->settings()->get());

        return ResponseHelper::successResponse([
            'settings' => $settingResourceCollection,
        ]);
    }

    public function update(
        UserSettingServiceInterface $userSettingService,
        UpdateSettingsValuesRequest $request,
        string $user_id
    ): JsonResponse {
        $validated = $request->validated();
        $settingsData = $validated['settingsData'];
        $isValidSettingsData = $userSettingService->validateSettingsData($settingsData);

        if (!$isValidSettingsData) {
            return ResponseHelper::errorResponse(400, ['Некорректные входные данные']);
        }

        if (!$userSettingService->updateSettings($user_id, $settingsData)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении настроек']);
        }

        return ResponseHelper::successResponse([], 'Настройки успешно обновлены');
    }
}
