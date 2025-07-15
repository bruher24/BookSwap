<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request, BookService $bookService): View
    {
        $filters = $bookService->getFilterFromRequest($request);

        $books = $bookService->where($filters);
        $params = $bookService->params();

        return view('books.index', compact('books', 'params', 'filters'));
    }

    public function store(StoreBookRequest $request, BookService $bookService): JsonResponse
    {
        $validated = $request->validated();
        $book = $bookService->create($validated);
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при сохранении книги',
            ]);
        }

        return response()->json([
            'success' => true,
            'errors' => [
                'Книга успешно сохранена',
            ],
        ]);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'success' => true,
            'book' => $book,
        ]);
    }

    public function update(UpdateBookRequest $request, BookService $bookService, Book $book)
    {
        $validated = $request->validated();
        $bookService->update($book, $validated);
    }

    public function delete(BookService $bookService, Book $book): JsonResponse
    {
        if ($bookService->delete($book)) {
            return response()->json([
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }
}
