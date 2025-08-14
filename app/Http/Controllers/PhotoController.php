<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        $covers = $photoService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(PhotoServiceInterface $photoService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $photoService->create($validated);
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
    public function show(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $cover = $photoService->get($id);
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
    public function edit(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PhotoServiceInterface $photoService, UpdatePhotoRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $photoService->update($id, $validated);
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
    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $deleted = $photoService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
