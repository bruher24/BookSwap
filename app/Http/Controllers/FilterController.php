<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\FilterResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\FilterServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FilterController extends Controller
{
    public function index(FilterServiceInterface $filterService): JsonResponse
    {
        $filters = $filterService->getAll();
        $filterResourceCollection = FilterResource::collection($filters);
        $data = ['filters' => $filterResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(FilterServiceInterface $filterService, StoreFilterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $filter = $filterService->create($validated);

        if (!$filter) {
            $errors = ['Ошибка при создании фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $filterResource = new FilterResource($filter);
        $data = ['filter' => $filterResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(FilterServiceInterface $filterService, string $id): JsonResponse
    {
        $filter = $filterService->get($id);

        if (!$filter) {
            $errors = ['Ошибка при получении фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $filterResource = new FilterResource($filter);
        $data = ['filter' => $filterResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        FilterServiceInterface $filterService,
        UpdateFilterRequest    $request,
        string                 $id
    ): JsonResponse
    {
        $validated = $request->validated();
        $filter = $filterService->update($id, $validated);

        if (!$filter) {
            $errors = ['Ошибка при обновлении фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $filterResource = new FilterResource($filter);
        $data = ['filter' => $filterResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(FilterServiceInterface $filterService, string $id): JsonResponse
    {
        if (!$filterService->delete($id)) {
            $errors = ['Ошибка при удалении фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
