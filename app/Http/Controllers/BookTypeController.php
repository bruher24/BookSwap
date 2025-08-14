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
            'booktypes' => BookTypeEnum::toPrettyArray(),
        ]);
    }

    public function create(BookTypeServiceInterface $bookTypeService): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);
        if (!$bookType) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при создании обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $bookType,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $bookType = $bookTypeService->get($id);
        if (!$bookType) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при получении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success', [
            'cover' => $bookType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        // TODO: вернуть набор полей формы
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        BookTypeServiceInterface $bookTypeService,
        UpdateBookTypeRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        $updated = $bookTypeService->update($id, $validated);
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
    public function destroy(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $deleted = $bookTypeService->delete($id);
        if (!$deleted) {
            return ResponseHelper::errorResponse([
                'ERR' => 'Ошибка при удалении обложки',
            ]);
        }
        return ResponseHelper::successResponse('Success');
    }
}
