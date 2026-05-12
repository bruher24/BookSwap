<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Requests\WhereBookRequest;
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
        $data = ['books' => BookResource::collection($books)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        Gate::authorize('create', [Book::class, $request->input('user_id')]);

        $validated = $request->validated();
        $book = $bookService->create($validated);

        if (!$book) {
            $errors = ['Ошибка при создании книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        $data = ['book' => new BookResource($book)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Book $book): JsonResponse
    {
        $data = ['book' => new BookResource($book)];

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

        $data = ['book' => new BookResource($book)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookServiceInterface $bookService, string $bookId): JsonResponse
    {
        $book = $bookService->get($bookId);

        if (!$book) {
            return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $book);

        if (!$bookService->delete($book)) {
            $errors = ['Ошибка при удалении книги'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        return (new SuccessResource())->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function where(WhereBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();

        $books = $bookService->where($validated);
        $data = ['books' => BookResource::collection($books)];

        return (new SuccessResource($data))->response()->setStatusCode(Response::HTTP_OK);
    }
}
