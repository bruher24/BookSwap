<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreCoverRequest;
use App\Http\Requests\UpdateCoverRequest;
use App\Http\Resources\CoverResource;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;

class CoverController extends Controller
{
    public function index(CoverServiceInterface $coverService): JsonResponse
    {
        $covers = $coverService->getAll();
        $coverResourceCollection = CoverResource::collection($covers);

        return ResponseHelper::successResponse([
            'covers' => $coverResourceCollection,
        ]);
    }

    public function store(CoverServiceInterface $coverService, StoreCoverRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $cover = $coverService->create($validated);
        if (!$cover) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании обложки']);
        }
        $coverResource = new CoverResource($cover);

        return ResponseHelper::successResponse([
            'cover' => $coverResource,
        ], 'Обложка успешно создана');
    }

    public function show(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $cover = $coverService->get($id);
        if (!$cover) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении обложки']);
        }
        $coverResource = new CoverResource($cover);

        return ResponseHelper::successResponse([
            'cover' => $coverResource,
        ]);
    }

    public function update(CoverServiceInterface $coverService, UpdateCoverRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        if (!$coverService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении обложки']);
        }

        return ResponseHelper::successResponse([], 'Обложка успешно обновлена');
    }

    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        if (!$coverService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении обложки']);
        }

        return ResponseHelper::successResponse([], 'Обложка успешно удалена');
    }
}
