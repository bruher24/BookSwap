<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->getAll();
        $settingResourceCollection = SettingResource::collection($settings);

        return ResponseHelper::successResponse([
            'settings' => $settingResourceCollection,
        ]);
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $setting = $settingService->create($validated);
        if (!$setting) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании настройки']);
        }
        $settingResource = new SettingResource($setting);

        return ResponseHelper::successResponse([
            'setting' => $settingResource,
        ], 'Настройка успешно создана');
    }

    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $setting = $settingService->get($id);
        if (!$setting) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении настройки']);
        }
        $settingResource = new SettingResource($setting);

        return ResponseHelper::successResponse([
            'setting' => $settingResource,
        ]);
    }

    public function update(
        SettingServiceInterface $settingService,
        UpdateSettingRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$settingService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении настройки']);
        }

        return ResponseHelper::successResponse([], 'Настройка успешно обновлена');
    }

    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        if (!$settingService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении настройки']);
        }

        return ResponseHelper::successResponse([], 'Настройка успешно удалена');
    }
}
