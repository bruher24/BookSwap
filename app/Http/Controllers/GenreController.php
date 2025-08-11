<?php

namespace App\Http\Controllers;

use App\Interfaces\BookServiceInterface;
use App\Interfaces\GenreServiceInterface;
use App\Models\Genre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenreController extends Controller
{
    public function index(GenreServiceInterface $genreService): View
    {
        $genres = $genreService->getAll();
        return view('genres.index', compact('genres'));
    }

    public function books(Request $request, BookServiceInterface $bookService, Genre $genre): View
    {
        $filters = $bookService->getFilterFromRequest($request);

        [$books, $params] = $bookService->byGenre($genre, $filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
