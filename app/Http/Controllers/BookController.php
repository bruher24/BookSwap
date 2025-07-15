<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class BookController extends Controller
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $this->bookService->getFilterFromRequest($request);

        $books = $this->bookService->where($filters);
        $params = $this->bookService->params();

        return view('books.index', compact('books', 'params', 'filters'));
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $book = $this->bookService->create($validated);
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

    public function update(UpdateBookRequest $request, Book $book)
    {
        $validated = $request->validated();
        $this->bookService->update($book, $validated);
    }

    public function delete(Book $book): JsonResponse
    {
        if ($this->bookService->delete($book)) {
            return response()->json([
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }
}
