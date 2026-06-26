<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Requests\WhereBookRequest;
use App\Http\Resources\BookResource;
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

        return BookResource::collection($books)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * @psalm-suppress PossiblyNullPropertyFetch
     */
    public function store(StoreBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        Gate::authorize('create', Book::class);

        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $book = $bookService->create($validated);

        if (!$book) {
            return $this->errorResponse('Ошибка при создании книги', Response::HTTP_BAD_REQUEST);
        }

        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Book $book): JsonResponse
    {
        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateBookRequest $request, BookServiceInterface $bookService, Book $book): JsonResponse
    {
        Gate::authorize('update', $book);

        $validated = $request->validated();
        $book = $bookService->update($book, $validated);

        if (!$book) {
            return $this->errorResponse('Ошибка при обновлении книги', Response::HTTP_BAD_REQUEST);
        }

        return (new BookResource($book))->response()->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(BookServiceInterface $bookService, int $bookId): JsonResponse
    {
        $book = $bookService->get($bookId);

        if (!$book) {
            return $this->successResponse(Response::HTTP_ACCEPTED);
        }

        Gate::authorize('delete', $book);

        if (!$bookService->delete($book)) {
            return $this->errorResponse('Ошибка при удалении книги', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(Response::HTTP_ACCEPTED);
    }

    public function where(WhereBookRequest $request, BookServiceInterface $bookService): JsonResponse
    {
        $validated = $request->validated();

        $books = $bookService->where($validated);

        return BookResource::collection($books)->response()->setStatusCode(Response::HTTP_OK);
    }
}
