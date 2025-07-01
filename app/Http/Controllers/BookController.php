<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct()
    {
        $this->bookService = new BookService();
    }

    public function index(Request $request): View
    {
        $filters = [];
        if ($request->isMethod('POST')) {
            $inputFilters = $request->except('_token');
            $filters = $this->bookService->formatFilters($inputFilters);
        }
        $books = $this->bookService->where($filters);
        $allBooks = $this->bookService->getAll();
        $params = $this->bookService->params($allBooks);
        return view('books.index', compact('books', 'params', 'filters'));
    }

    public function store(StoreBookRequest $request): RedirectResponse|JsonResponse
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

    public function show(int $bookId)
    {
        $book = $this->bookService->get($bookId);
        return response()->json([
            'success' => true,
            'book' => $book,
        ]);
    }

    public function edit(Book $book)
    {
    }

    public function update(Request $request, Book $book)
    {
    }

    public function delete(int $bookId): JsonResponse
    {
        if ($this->bookService->delete($bookId)) {
            return response()->json([
                'success' => true,
            ]);
        }
        return response()->json([
            'success' => false,
        ]);
    }
}
