<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserSettingValueRequest;
use App\Http\Resources\SettingResource;
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

        return SettingResource::collection($userSettings)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UserSettingServiceInterface $userSettingService, UpdateUserSettingValueRequest $request, User $user, Setting $setting): JsonResponse
    {
        Gate::authorize('settings', $user);

        $validated = $request->validated();
        $updated = $userSettingService->updateSetting($user, $setting, $validated['value']);

        if (!$updated) {
            return $this->errorResponse('Ошибка при обновлении настроек', Response::HTTP_BAD_REQUEST);
        }

        return (new SettingResource($setting))->response()->setStatusCode(Response::HTTP_OK);
    }
}
