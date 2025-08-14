<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->getAll();
        return ResponseHelper::successResponse('Success', [
            'settings' => $settings,
        ]);
    }

    public function store(SettingServiceInterface $settingService, Request $request): JsonResponse
    {
        $setting = $settingService->create($request->all());
        if (!$setting) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании настройки',
            ]);
        }
        return ResponseHelper::successResponse('Настройка успешно создана');
    }

    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $setting = $settingService->get($id);
        if (!$setting) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении настройки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'setting' => $setting,
        ]);
    }

    public function update(SettingServiceInterface $settingService, Request $request, string $id): JsonResponse
    {
        $updated = $settingService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении настройки',
            ]);
        }
        return ResponseHelper::successResponse('Настройка успешно обновлена');
    }

    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $deleted = $settingService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении настройки',
            ]);
        }
        return ResponseHelper::successResponse('Настройка успешно удалена');
    }
}
