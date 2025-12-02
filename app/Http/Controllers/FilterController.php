<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\FilterResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\FilterServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class FilterController extends Controller
{
    public function index(FilterServiceInterface $filterService): JsonResource
    {
        $filters = $filterService->getAll();
        $filterResourceCollection = FilterResource::collection($filters);
        $data = ['filters' => $filterResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(FilterServiceInterface $filterService, StoreFilterRequest $request): JsonResource
    {
        $validated = $request->validated();
        $filter = $filterService->create($validated);

        if (!$filter) {
            $errors = ['Ошибка при создании фильтра'];
            return new FailureResource(['errors' => $errors]);
        }

        $filterResource = new FilterResource($filter);
        $data = ['filter' => $filterResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(FilterServiceInterface $filterService, string $id): JsonResource
    {
        $filter = $filterService->get($id);

        if (!$filter) {
            $errors = ['Ошибка при получении фильтра'];
            return new FailureResource(['errors' => $errors]);
        }

        $filterResource = new FilterResource($filter);
        $data = ['filter' => $filterResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(
        FilterServiceInterface $filterService,
        UpdateFilterRequest $request,
        string $id
    ): JsonResource {
        $validated = $request->validated();

        if (!$filterService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении фильтра'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(FilterServiceInterface $filterService, string $id): JsonResource
    {
        if (!$filterService->delete($id)) {
            $errors = ['Ошибка при удалении фильтра'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
