<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\AuthorService;
use App\Services\BookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function __construct(private readonly AuthorService $authorService)
    {
    }

    public function index(): View
    {
        $authors = $this->authorService->getAll();
        return view('authors.index', compact('authors'));
    }

    public function getAuthors(): string
    {
        return $this->authorService->getAll()->toJson(JSON_PRETTY_PRINT);
    }

    public function books(Request $request, BookService $bookService, Author $author): View|RedirectResponse
    {
        $filters = $this->authorService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($author, $filters);

        return view('authors.books', compact('books', 'params', 'filters', 'author'));
    }
}
