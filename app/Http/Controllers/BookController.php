<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\FailureResource;
use App\Http\Resources\SuccessResource;
use App\Interfaces\BookServiceInterface;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

final class BookController extends Controller
{
    public function index(BookServiceInterface $bookService): JsonResponse
    {
        $books = $bookService->getAll();
        $bookResourceCollection = BookResource::collection($books);
        $data = ['books' => $bookResourceCollection];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);

        if (!$book) {
            $errors = ['Ошибка при создании книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $bookResource = new BookResource($book);
        $data = ['book' => $bookResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BookServiceInterface $bookService, string $id): JsonResponse
    {
        $book = $bookService->get($id);

        if (!$book) {
            $errors = ['Ошибка при получении книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_NOT_FOUND);
        }

        $bookResource = new BookResource($book);
        $data = ['book' => $bookResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, Book $book): JsonResponse
    {
        Gate::authorize('update', $book);
        $validated = $request->validated();

        $book = $bookService->update($book, $validated);
        if (!$book) {
            $errors = ['Ошибка при обновлении книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $bookResource = new BookResource($book);
        $data = ['book' => $bookResource];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookServiceInterface $bookService, Book $book): JsonResponse
    {
        Gate::authorize('delete', $book);

        if (!$bookService->delete($book)) {
            $errors = ['Ошибка при удалении книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
