<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $settings = $settingService->getAll();
        return ResponseHelper::successResponse('Success', [
            'settings' => $settings,
        ]);
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
}
