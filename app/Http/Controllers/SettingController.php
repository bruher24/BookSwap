<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->getAll();
        $data = ['settings' => SettingResource::collection($settings)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResponse
    {
        Gate::authorize('create', Setting::class);

        $validated = $request->validated();
        $setting = $settingService->create($validated);

        if (!$setting) {
            $errors = ['Ошибка при создании настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['setting' => new SettingResource($setting)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Setting $setting): JsonResponse
    {
        $data = ['setting' => new SettingResource($setting)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(SettingServiceInterface $settingService, UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        Gate::authorize('update', $setting);

        $validated = $request->validated();
        $setting = $settingService->update($setting, $validated);

        if (!$setting) {
            $errors = ['Ошибка при обновлении настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['setting' => new SettingResource($setting)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(SettingServiceInterface $settingService, Setting $setting): JsonResponse
    {
        Gate::authorize('delete', $setting);

        if (!$settingService->delete($setting)) {
            $errors = ['Ошибка при удалении настройки'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
