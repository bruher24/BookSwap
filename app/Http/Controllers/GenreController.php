<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Services\BookService;
use App\Services\GenreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(GenreService $genreService): View
    {
        $genres = $genreService->getAll();
        return view('genres.index', compact('genres'));
    }

    public function books(Request $request, BookService $bookService, Genre $genre): View|RedirectResponse
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
