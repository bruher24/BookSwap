<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Interfaces\SettingServiceInterface;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        Gate::authorize('viewAny', Setting::class);

        $settings = $settingService->getAll();

        return SettingResource::collection($settings)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResponse
    {
        Gate::authorize('create', Setting::class);

        $validated = $request->validated();
        $setting = $settingService->create($validated);

        if (!$setting) {
            return $this->errorResponse('Ошибка при создании настройки', Response::HTTP_BAD_REQUEST);
        }

        return (new SettingResource($setting))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Setting $setting): JsonResponse
    {
        Gate::authorize('view', $setting);

        return (new SettingResource($setting))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(SettingServiceInterface $settingService, UpdateSettingRequest $request, Setting $setting): JsonResponse
    {
        Gate::authorize('update', $setting);

        $validated = $request->validated();
        $setting = $settingService->update($setting, $validated);

        if (!$setting) {
            return $this->errorResponse('Ошибка при обновлении настройки', Response::HTTP_BAD_REQUEST);
        }

        return (new SettingResource($setting))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(SettingServiceInterface $settingService, int $settingId): JsonResponse
    {
        $setting = $settingService->get($settingId);

        if (!$setting) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $setting);

        if (!$settingService->delete($setting)) {
            return $this->errorResponse('Ошибка при удалении настройки', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}

// TODO: byUser, updateForUser
