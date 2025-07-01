<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthorController extends Controller
{
    private AuthorService $authorService;

    public function __construct()
    {
        $this->authorService = new AuthorService();
    }

    public function index():View
    {
        $authors = Author::all();
        return view('authors.index', compact('authors'));
    }

    public function getAuthors(Request $request): string
    {
        return Author::all()->toJson(JSON_PRETTY_PRINT);
    }

    public function books(Request $request, int $authorId): View | RedirectResponse
    {
        $filters = [];
        if ($request->isMethod('POST')) {
            $inputFilters = $request->except('_token');
            $filters = $this->authorService->formatFilters($inputFilters);
        }
        $filters['author'] = [$authorId];
        $author = $this->authorService->get($authorId);
        if (!$author) {
            return back()->withErrors(['error' => 'Автор не найден.']);
        }

        [$books, $params] = $this->authorService->books($filters);

        return view('authors.books', compact('books', 'params', 'filters', 'author'));
    }
}
