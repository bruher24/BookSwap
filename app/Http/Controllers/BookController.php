<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\BookServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

final class BookController extends Controller
{
    public function index(BookServiceInterface $bookService): JsonResource
    {
        $books = $bookService->getAll();
        $bookResourceCollection = BookResource::collection($books);
        $data = ['books' => $bookResourceCollection];

        return new SuccessResource(['data' => $data]);
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResource
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);

        if (!$book) {
            $errors = ['Ошибка при создании книги'];
            return new FailureResource(['errors' => $errors]);
        }

        $bookResource = new BookResource($book);
        $data = ['book' => $bookResource];

        return new SuccessResource(['data' => $data]);
    }

    public function show(BookServiceInterface $bookService, string $id): JsonResource
    {
        $book = $bookService->get($id);

        if (!$book) {
            $errors = ['Ошибка при получении книги'];
            return new FailureResource(['errors' => $errors]);
        }

        $bookResource = new BookResource($book);
        $data = ['book' => $bookResource];

        return new SuccessResource(['data' => $data]);
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, string $id): JsonResource
    {
        $validated = $request->validated();

        if (!$bookService->update($id, $validated)) {
            $errors = ['Ошибка при обновлении книги'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }

    public function destroy(BookServiceInterface $bookService, string $id): JsonResource
    {
        if (!$bookService->delete($id)) {
            $errors = ['Ошибка при удалении книги'];
            return new FailureResource(['errors' => $errors]);
        }

        return new SuccessResource([]);
    }
}
