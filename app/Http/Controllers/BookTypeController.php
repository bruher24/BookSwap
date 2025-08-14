<?php

namespace App\Http\Controllers;

use App\Enums\BookTypeEnum;
use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Interfaces\BookTypeServiceInterface;
use Illuminate\Http\JsonResponse;

class BookTypeController extends Controller
{
    public function index(): JsonResponse
    {
        return ResponseHelper::successResponse('Success', [
            'bookTypes' => BookTypeEnum::toPrettyArray(),
        ]);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);
        if (!$bookType) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании типа',
            ]);
        }
        return ResponseHelper::successResponse('Тип успешно создан', [
            'bookType' => $bookType,
        ]);
    }

    public function show(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $bookType = $bookTypeService->get($id);
        if (!$bookType) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении типа',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'bookType' => $bookType,
        ]);
    }

    public function update(
        BookTypeServiceInterface $bookTypeService,
        UpdateBookTypeRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        $updated = $bookTypeService->update($id, $validated);
        if (!$updated) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при обновлении типа',
            ]);
        }
        return ResponseHelper::successResponse('Тип успешно обновлен');
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $deleted = $bookTypeService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении типа',
            ]);
        }
        return ResponseHelper::successResponse('Тип успешно удален');
    }
}
