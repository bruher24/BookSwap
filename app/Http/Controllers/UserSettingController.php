<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsValuesRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\UserServiceInterface;
use App\Interfaces\UserSettingServiceInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class UserSettingController extends Controller
{
    public function index(UserServiceInterface $userService, string $user_id): JsonResponse
    {
        $user = $userService->get($user_id);

        if (!$user instanceof User) {
            $errors = ['Пользователь не найден'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $settingResourceCollection = SettingResource::collection($user->settings()->get());
        $data = ['settings' => $settingResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
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
            $errors = ['Некорректные входные данные'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        if (!$userSettingService->updateSettings($user_id, $settingsData)) {
            $errors = ['Ошибка при обновлении настроек'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
