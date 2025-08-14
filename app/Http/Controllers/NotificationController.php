<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\NotificationServiceInterface;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(NotificationServiceInterface $notificationService): JsonResponse
    {
        $covers = $notificationService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(NotificationServiceInterface $notificationService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(NotificationServiceInterface $notificationService): JsonResponse
    {
        $validated = $request->validated();
        $cover = $notificationService->create($validated);
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
    public function show(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $cover = $notificationService->get($id);
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
    public function edit(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $notificationService->update($id, $validated);
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
    public function destroy(NotificationServiceInterface $notificationService, string $id): JsonResponse
    {
        $deleted = $notificationService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
