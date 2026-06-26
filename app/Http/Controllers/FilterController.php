<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Http\Resources\FilterResource;
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

        return FilterResource::collection($filters)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(FilterServiceInterface $filterService, StoreFilterRequest $request): JsonResponse
    {
        Gate::authorize('create', Filter::class);

        $validated = $request->validated();
        $filter = $filterService->create($validated);

        if (!$filter) {
            return $this->errorResponse('Ошибка при создании фильтра', Response::HTTP_BAD_REQUEST);
        }

        return (new FilterResource($filter))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Filter $filter): JsonResponse
    {
        return (new FilterResource($filter))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(FilterServiceInterface $filterService, UpdateFilterRequest $request, Filter $filter): JsonResponse
    {
        Gate::authorize('update', $filter);

        $validated = $request->validated();
        $filter = $filterService->update($filter, $validated);

        if (!$filter) {
            return $this->errorResponse('Ошибка при обновлении фильтра', Response::HTTP_BAD_REQUEST);
        }

        return (new FilterResource($filter))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(FilterServiceInterface $filterService, int $filterId): JsonResponse
    {
        $filter = $filterService->get($filterId);

        if (!$filter) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $filter);

        if (!$filterService->delete($filter)) {
            return $this->errorResponse('Ошибка при удалении фильтра', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
