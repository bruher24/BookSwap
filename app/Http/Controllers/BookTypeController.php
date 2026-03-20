<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\BookTypeServiceInterface;
use App\Models\BookType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class BookTypeController extends Controller
{
    public function index(BookTypeServiceInterface $bookTypeService): JsonResponse
    {
        Gate::authorize('viewAny', BookType::class);

        $bookTypes = $bookTypeService->getAll();
        $data = ['bookTypes' => BookTypeResource::collection($bookTypes)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        Gate::authorize('create', BookType::class);

        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);

        if (!$bookType) {
            $errors = ['Ошибка при создании типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['bookType' => new BookTypeResource($bookType)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BookType $bookType): JsonResponse
    {
        Gate::authorize('view', $bookType);

        $data = ['bookType' => new BookTypeResource($bookType)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(BookTypeServiceInterface $bookTypeService, UpdateBookTypeRequest $request, BookType $bookType): JsonResponse
    {
        Gate::authorize('update', $bookType);

        $validated = $request->validated();
        $bookType = $bookTypeService->update($bookType, $validated);

        if (!$bookType) {
            $errors = ['Ошибка при обновлении типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['bookType' => new BookTypeResource($bookType)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, string $bookTypeId): JsonResponse
    {
        $bookType = $bookTypeService->get($bookTypeId);

        if (!$bookType) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $bookType);

        if (!$bookTypeService->delete($bookType)) {
            $errors = ['Ошибка при удалении типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
