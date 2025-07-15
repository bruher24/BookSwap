<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\GenreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GenreController extends Controller
{
    public function __construct(private readonly GenreService $genreService)
    {
    }

    public function index()
    {
        $genres = $this->genreService->getAll();
        return view('genres.index', compact('genres'));
    }

    public function books(Request $request, BookService $bookService, int $genreId): View|RedirectResponse
    {
        $genre = $this->genreService->get($genreId);
        if (!$genre) {
            return back()->withErrors(['error' => 'Жанр не найден.']);
        }

        $filters = $this->genreService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genreId, $filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
