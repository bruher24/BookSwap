<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Interfaces\SettingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SettingServiceInterface $settingService): JsonResponse
    {
        $covers = $settingService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(SettingServiceInterface $settingService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(SettingServiceInterface $settingService, StoreSettingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $settingService->create($validated);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $cover = $settingService->get($id);
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $cover,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        SettingServiceInterface $settingService,
        UpdateSettingRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        $updated = $settingService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SettingServiceInterface $settingService, string $id): JsonResponse
    {
        $deleted = $settingService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
