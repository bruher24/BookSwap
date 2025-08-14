<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\CoverServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoverController extends Controller
{
    public function store(CoverServiceInterface $coverService, Request $request): JsonResponse
    {
        $cover = $coverService->create($request->all());
        if (!$cover) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании обложки',
            ]);
        }
        return ResponseHelper::successResponse('Обложка успешно создана', [
            'cover' => $cover,
        ]);
    }

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

    public function destroy(CoverServiceInterface $coverService, string $id): JsonResponse
    {
        $deleted = $coverService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Обложка успешно удалена');
    }
}
