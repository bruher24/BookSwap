<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PhotoResource;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

    public function store(PhotoServiceInterface $photoService, Request $request): JsonResponse
    {
        $photo = $photoService->create($request->all());
        if (!$photo) {
            return ResponseHelper::errorResponse(['Ошибка при создании фото']);
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
            return ResponseHelper::errorResponse(['Ошибка при получении фото']);
        }
        $photoResource = new PhotoResource($photo);
        return ResponseHelper::successResponse([
            'photo' => $photoResource,
        ]);
    }

    public function update(PhotoServiceInterface $photoService, Request $request, string $id): JsonResponse
    {
        if (!$photoService->update($id, $request->all())) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении фото']);
        }
        return ResponseHelper::successResponse([], 'Фото успешно обновлено');
    }

    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        if (!$photoService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении фото']);
        }
        return ResponseHelper::successResponse([], 'Фото успешно удалено');
    }
}
