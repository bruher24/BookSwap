<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
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
        $params = $this->bookService->params();
        return view('books.index', compact('books', 'params', 'filters'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show(Book $book)
    {
    }

    public function edit(Book $book)
    {
    }

    public function update(Request $request, Book $book)
    {
    }

    public function destroy(Book $book)
    {
    }
}
