<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Interfaces\BookTypeServiceInterface;
use Illuminate\Http\JsonResponse;

class BookTypeController extends Controller
{
    public function index(BookTypeServiceInterface $bookTypeService): JsonResponse
    {
        $bookTypes = $bookTypeService->getAll();
        $bookTypeResourceCollection = BookTypeResource::collection($bookTypes);
        return ResponseHelper::successResponse([
            'bookTypes' => $bookTypeResourceCollection,
        ]);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);
        if (!$bookType) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании типа']);
        }
        $bookTypeResource = new BookTypeResource($bookType);
        return ResponseHelper::successResponse([
            'bookType' => $bookTypeResource,
        ], 'Тип успешно создан');
    }

    public function show(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $bookType = $bookTypeService->get($id);
        if (!$bookType) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении типа']);
        }
        $bookTypeResource = new BookTypeResource($bookType);
        return ResponseHelper::successResponse([
            'bookType' => $bookTypeResource,
        ]);
    }

    public function update(
        BookTypeServiceInterface $bookTypeService,
        UpdateBookTypeRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$bookTypeService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении типа']);
        }
        return ResponseHelper::successResponse([], 'Тип успешно обновлен');
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        if (!$bookTypeService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении типа']);
        }
        return ResponseHelper::successResponse([], 'Тип успешно удален');
    }
}
