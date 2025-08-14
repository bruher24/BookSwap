<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreCoverRequest;
use App\Http\Requests\UpdateCoverRequest;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;

class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        $covers = $coverService->getAll();
        return ResponseHelper::successResponse('Success', [
            'covers' => $covers,
        ]);
    }

    public function create(CoverServiceInterface $coverService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $coverService->create($validated);
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
    public function show(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $cover = $coverService->get($id);
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
    public function edit(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CoverServiceInterface $coverService, UpdateCoverRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        $updated = $coverService->update($id, $validated);
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
    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $deleted = $coverService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
