<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Interfaces\BookTypeServiceInterface;
use App\Models\BookType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class BookTypeController extends Controller
{
    public function index(BookTypeServiceInterface $bookTypeService): JsonResponse
    {
        $bookTypes = $bookTypeService->getAll();

        return BookTypeResource::collection($bookTypes)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        Gate::authorize('create', BookType::class);

        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);

        if (!$bookType) {
            return $this->errorResponse('Ошибка при создании типа', Response::HTTP_BAD_REQUEST);
        }

        return (new BookTypeResource($bookType))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BookType $bookType): JsonResponse
    {
        return (new BookTypeResource($bookType))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(BookTypeServiceInterface $bookTypeService, UpdateBookTypeRequest $request, BookType $bookType): JsonResponse
    {
        Gate::authorize('update', $bookType);

        $validated = $request->validated();
        $bookType = $bookTypeService->update($bookType, $validated);

        if (!$bookType) {
            return $this->errorResponse('Ошибка при обновлении типа', Response::HTTP_BAD_REQUEST);
        }

        return (new BookTypeResource($bookType))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, int $bookTypeId): JsonResponse
    {
        $bookType = $bookTypeService->get($bookTypeId);

        if (!$bookType) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $bookType);

        if (!$bookTypeService->delete($bookType)) {
            return $this->errorResponse('Ошибка при удалении типа', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }
}
