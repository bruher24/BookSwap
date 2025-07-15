<?php

namespace App\Http\Controllers;

use App\Models\Genre;
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

    public function index(): View
    {
        $genres = $this->genreService->getAll();
        return view('genres.index', compact('genres'));
    }

    public function books(Request $request, BookService $bookService, Genre $genre): View|RedirectResponse
    {
        $filters = $this->genreService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
