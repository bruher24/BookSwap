<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;

class PhotoController extends Controller
{
    public function index(PhotoServiceInterface $photoService): JsonResponse
    {
        $photos = $photoService->getAll();
        $photoResourceCollection = PhotoResource::collection($photos);
        return ResponseHelper::successResponse([
            'photos' => $photoResourceCollection,
        ]);
    }

    public function store(PhotoServiceInterface $photoService, StorePhotoRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $photo = $photoService->create($validated);
        if (!$photo) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании фото']);
        }
        $photoResource = new PhotoResource($photo);
        return ResponseHelper::successResponse([
            'photo' => $photoResource,
        ], 'Фото успешно создано');
    }

    public function show(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $photo = $photoService->get($id);
        if (!$photo) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении фото']);
        }
        $photoResource = new PhotoResource($photo);
        return ResponseHelper::successResponse([
            'photo' => $photoResource,
        ]);
    }

    public function update(PhotoServiceInterface $photoService, UpdatePhotoRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();
        if (!$photoService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении фото']);
        }
        return ResponseHelper::successResponse([], 'Фото успешно обновлено');
    }

    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        if (!$photoService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении фото']);
        }
        return ResponseHelper::successResponse([], 'Фото успешно удалено');
    }
}
