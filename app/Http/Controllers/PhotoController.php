<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\PhotoServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function store(PhotoServiceInterface $photoService, Request $request): JsonResponse
    {
        $photo = $photoService->create($request->all());
        if (!$photo) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании фото',
            ]);
        }
        return ResponseHelper::successResponse('Фото успешно создано', [
            'photo' => $photo,
        ]);
    }

    public function show(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $photo = $photoService->get($id);
        if (!$photo) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении фото',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'photo' => $photo,
        ]);
    }

    public function destroy(PhotoServiceInterface $photoService, string $id): JsonResponse
    {
        $deleted = $photoService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении фото',
            ]);
        }
        return ResponseHelper::successResponse('Фото успешно удалено');
    }
}
