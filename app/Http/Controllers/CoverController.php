<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\CoverResource;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(CoverServiceInterface $coverService, Request $request): JsonResponse
    {
        $cover = $coverService->create($request->all());
        $coverResource = new CoverResource($cover);
        if (!$cover) {
            return ResponseHelper::errorResponse(['Ошибка при создании обложки']);
        }
        return ResponseHelper::successResponse([
            'cover' => $coverResource,
        ], 'Обложка успешно создана');
    }

    public function show(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $cover = $coverService->get($id);
        $coverResource = new CoverResource($cover);
        if (!$cover) {
            return ResponseHelper::errorResponse(['Ошибка при получении обложки']);
        }
        return ResponseHelper::successResponse([
            'cover' => $coverResource,
        ]);
    }

    public function update(CoverServiceInterface $coverService, Request $request, string $id): JsonResponse
    {
        $updated = $coverService->update($id, $request->all());
        if (!$updated) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении обложки']);
        }
        return ResponseHelper::successResponse([], 'Обложка успешно обновлена');
    }

    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $deleted = $coverService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse(['Ошибка при удалении обложки']);
        }
        return ResponseHelper::successResponse([], 'Обложка успешно удалена');
    }
}
