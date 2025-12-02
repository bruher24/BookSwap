<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsValuesRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserSettingServiceInterface;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

final class UserSettingController extends Controller
{
    public function index(UserServiceInterface $userService, string $user_id): JsonResource
    {
        $user = $userService->get($user_id);

        if (!$user instanceof User) {
            $errors = ['Пользователь не найден'];
            return new FailureResource(['errors' => $errors]);
        }

        $settingResourceCollection = SettingResource::collection($user->settings()->get());
        $data = ['settings' => $settingResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function update(
        UserSettingServiceInterface $userSettingService,
        UpdateSettingsValuesRequest $request,
        string $user_id
    ): JsonResource {
        $validated = $request->validated();
        $settingsData = $validated['settingsData'];
        $isValidSettingsData = $userSettingService->validateSettingsData($settingsData);

        if (!$isValidSettingsData) {
            $errors = ['Некорректные входные данные'];
            return new FailureResource(['errors' => $errors]);
        }

        if (!$userSettingService->updateSettings($user_id, $settingsData)) {
            $errors = ['Ошибка при обновлении настроек'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
