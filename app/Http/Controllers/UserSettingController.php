<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserSettingValueRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\UserSettingServiceInterface;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class UserSettingController extends Controller
{
    public function index(User $user): JsonResponse
    {
        Gate::authorize('settings', $user);

        $userSettings = $user->settings()->get();
        $data = ['settings' => SettingResource::collection($userSettings)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UserSettingServiceInterface $userSettingService, UpdateUserSettingValueRequest $request, User $user, Setting $setting): JsonResponse
    {
        Gate::authorize('settings', $user);

        $validated = $request->validated();

        if (!$userSettingService->updateSetting($user, $setting, $validated['value'])) {
            $errors = ['Ошибка при обновлении настроек'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_OK);
    }
}
