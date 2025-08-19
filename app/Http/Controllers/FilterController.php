<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FilterResource;
use App\Interfaces\FilterServiceInterface;
use Illuminate\Http\JsonResponse;

class FilterController extends Controller
{
    public function index(FilterServiceInterface $filterService): JsonResponse
    {
        $filters = $filterService->getAll();
        $filterResourceCollection = FilterResource::collection($filters);

        return ResponseHelper::successResponse([
            'filters' => $filterResourceCollection,
        ]);
    }

    public function store(FilterServiceInterface $filterService, StoreFilterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $filter = $filterService->create($validated);
        if (!$filter) {
            return ResponseHelper::errorResponse(['Ошибка при создании фильтра']);
        }
        $filterResource = new FilterResource($filter);
        return ResponseHelper::successResponse([
            'filter' => $filterResource,
        ], 'Фильтр успешно создан');
    }

    public function show(FilterServiceInterface $filterService, string $id): JsonResponse
    {
        $filter = $filterService->get($id);
        if (!$filter) {
            return ResponseHelper::errorResponse(['Ошибка при получении фильтра']);
        }
        $filterResource = new FilterResource($filter);
        return ResponseHelper::successResponse([
            'filter' => $filterResource,
        ]);
    }

    public function update(
        FilterServiceInterface $filterService,
        UpdateFilterRequest $request,
        string $id
    ): JsonResponse {
        $validated = $request->validated();
        if (!$filterService->update($id, $validated)) {
            return ResponseHelper::errorResponse(['Ошибка при обновлении фильтра']);
        }
        return ResponseHelper::successResponse([], 'Фильтр успешно обновлен');
    }

    public function destroy(FilterServiceInterface $filterService, string $id): JsonResponse
    {
        if (!$filterService->delete($id)) {
            return ResponseHelper::errorResponse(['Ошибка при удалении фильтра']);
        }
        return ResponseHelper::successResponse([], 'Фильтр успешно удален');
    }
}
