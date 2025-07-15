<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\AuthorService;
use App\Services\BookService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AuthorController extends Controller
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

    public function books(Request $request, BookService $bookService, int $authorId): View|RedirectResponse
    {
        $author = $this->authorService->get($authorId);
        if (!$author) {
            return back()->withErrors(['error' => 'Автор не найден.']);
        }

        $filters = $this->authorService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byAuthor($authorId, $filters);

        return view('authors.books', compact('books', 'params', 'filters', 'author'));
    }
}
