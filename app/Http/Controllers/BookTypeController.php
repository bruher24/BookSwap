<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookTypeRequest;
use App\Http\Requests\UpdateBookTypeRequest;
use App\Http\Resources\BookTypeResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\BookTypeServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookTypeController extends Controller
{
    public function index(BookTypeServiceInterface $bookTypeService): JsonResource
    {
        $bookTypes = $bookTypeService->getAll();
        $bookTypeResourceCollection = BookTypeResource::collection($bookTypes);
        $data = ['bookTypes' => $bookTypeResourceCollection];

        return new SuccessResource($data);
    }

    public function store(BookTypeServiceInterface $bookTypeService, StoreBookTypeRequest $request): JsonResource
    {
        $validated = $request->validated();
        $bookType = $bookTypeService->create($validated);

        if (!$bookType) {
            $errors = ['Ошибка при создании типа'];
            return new FailureResource($errors);
        }

        $bookTypeResource = new BookTypeResource($bookType);
        $data = ['bookType' => $bookTypeResource];

        return new SuccessResource($data);
    }

    public function show(BookTypeServiceInterface $bookTypeService, string $id): JsonResource
    {
        $bookType = $bookTypeService->get($id);

        if (!$bookType) {
            $errors = ['Ошибка при получении типа'];
            return new FailureResource($errors);
        }

        $bookTypeResource = new BookTypeResource($bookType);
        $data = ['bookType' => $bookTypeResource];

        return new SuccessResource($data);
    }

    public function update(
        BookTypeServiceInterface $bookTypeService,
        UpdateBookTypeRequest $request,
        string $id
    ): JsonResource {
        $validated = $request->validated();

        if (!$bookTypeService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении типа'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }

    public function destroy(BookTypeServiceInterface $bookTypeService, string $id): JsonResource
    {
        if (!$bookTypeService->delete($id)) {
            $errors = ['Ошибка при удалении типа'];
            return new FailureResource($errors);
        }

        return new SuccessResource();
    }
}
