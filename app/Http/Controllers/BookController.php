<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function index(BookServiceInterface $bookService): JsonResponse
    {
        $books = $bookService->getAll();
        $bookResourceCollection = BookResource::collection($books);

        return ResponseHelper::successResponse([
            'books' => $bookResourceCollection,
        ]);
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);
        if (!$book) {
            return ResponseHelper::errorResponse(400, ['Ошибка при создании книги']);
        }
        $bookResource = new BookResource($book);

        return ResponseHelper::successResponse([
            'book' => $bookResource,
        ], 'Книга успешно создана');
    }

    public function show(BookServiceInterface $bookService, string $id): JsonResponse
    {
        $book = $bookService->get($id);
        if (!$book) {
            return ResponseHelper::errorResponse(400, ['Ошибка при получении книги']);
        }
        $bookResource = new BookResource($book);

        return ResponseHelper::successResponse([
            'book' => $bookResource,
        ]);
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, string $id): JsonResponse
    {
        $validated = $request->validated();
        if (!$bookService->update($id, $validated)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при обновлении книги']);
        }

        return ResponseHelper::successResponse([], 'Книга успешно обновлена');
    }

    public function destroy(BookServiceInterface $bookService, string $id): JsonResponse
    {
        if (!$bookService->delete($id)) {
            return ResponseHelper::errorResponse(400, ['Ошибка при удалении книги']);
        }

        return ResponseHelper::successResponse([], 'Книга успешно удалена');
    }
}
