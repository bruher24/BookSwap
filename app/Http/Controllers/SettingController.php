<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\SettingResource;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(SettingServiceInterface $settingService, Request $request): JsonResponse
    {
        $setting = $settingService->create($request->all());
        $settingResource = new SettingResource($setting);
        if (!$setting) {
            return ResponseHelper::errorResponse(['Ошибка при создании настройки']);
        }
        return ResponseHelper::successResponse([
            'setting' => $settingResource,
        ], 'Настройка успешно создана');
    }

    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $setting = $settingService->get($id);
        $settingResource = new SettingResource($setting);
        if (!$setting) {
            return ResponseHelper::errorResponse(['Ошибка при получении настройки']);
        }
        return ResponseHelper::successResponse([
            'setting' => $settingResource,
        ]);
    }

    public function update(SettingServiceInterface $settingService, Request $request, string $id): JsonResponse
    {
        $updated = $settingService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении настройки']);
        }
        return ResponseHelper::successResponse([], 'Настройка успешно обновлена');
    }

    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $deleted = $settingService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении настройки']);
        }
        return ResponseHelper::successResponse([], 'Настройка успешно удалена');
    }
}
