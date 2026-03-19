<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FailureResource;
use App\Http\Resources\FilterResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\FilterServiceInterface;
use App\Models\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class FilterController extends Controller
{
    public function index(FilterServiceInterface $filterService): JsonResponse
    {
        $filters = $filterService->getAll();
        $data = ['filters' => FilterResource::collection($filters)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(FilterServiceInterface $filterService, StoreFilterRequest $request): JsonResponse
    {
        Gate::authorize('create', Filter::class);

        $validated = $request->validated();
        $filter = $filterService->create($validated);

        if (!$filter) {
            $errors = ['Ошибка при создании фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['filter' => new FilterResource($filter)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Filter $filter): JsonResponse
    {
        $data = ['filter' => new FilterResource($filter)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(FilterServiceInterface $filterService, UpdateFilterRequest $request, Filter $filter): JsonResponse
    {
        Gate::authorize('update', $filter);

        $validated = $request->validated();
        $filter = $filterService->update($filter, $validated);

        if (!$filter) {
            $errors = ['Ошибка при обновлении фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['filter' => new FilterResource($filter)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(FilterServiceInterface $filterService, Filter $filter): JsonResponse
    {
        Gate::authorize('delete', $filter);

        if (!$filterService->delete($filter)) {
            $errors = ['Ошибка при удалении фильтра'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
