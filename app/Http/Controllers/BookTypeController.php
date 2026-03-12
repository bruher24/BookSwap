<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\BookTypeServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class BookTypeController extends Controller
{
    public function index(BookTypeServiceInterface $bookTypeService): JsonResponse
    {
        $bookTypes = $bookTypeService->getAll();
        $bookTypeResourceCollection = BookTypeResource::collection($bookTypes);
        $data = ['bookTypes' => $bookTypeResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);

        if (!$bookType) {
            $errors = ['Ошибка при создании типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $bookTypeResource = new BookTypeResource($bookType);
        $data = ['bookType' => $bookTypeResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        $bookType = $bookTypeService->get($id);

        if (!$bookType) {
            $errors = ['Ошибка при получении типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $bookTypeResource = new BookTypeResource($bookType);
        $data = ['bookType' => $bookTypeResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(
        BookTypeServiceInterface $bookTypeService,
        UpdateBookTypeRequest    $request,
        string                   $id
    ): JsonResponse
    {
        $validated = $request->validated();

        $bookType = $bookTypeService->update($id, $validated);
        if (!$bookType) {
            $errors = ['Ошибка при обновлении типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $bookTypeResource = new BookTypeResource($bookType);
        $data = ['bookType' => $bookTypeResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, string $id): JsonResponse
    {
        if (!$bookTypeService->delete($id)) {
            $errors = ['Ошибка при удалении типа'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
