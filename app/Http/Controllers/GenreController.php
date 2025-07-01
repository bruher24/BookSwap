<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use App\Services\GenreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GenreController extends Controller
{
    private GenreService $genreService;

    public function __construct()
    {
        $this->genreService = new GenreService();
    }

    public function index()
    {
        $genres = $this->genreService->getAll();
        return view('genres.index', compact('genres'));
    }

    public function books(Request $request, int $genreId): View | RedirectResponse
    {
        $filters = [];
        if ($request->isMethod('POST')) {
            $inputFilters = $request->except('_token');
            $filters = $this->genreService->formatFilters($inputFilters);
        }
        $filters['genre'] = [$genreId];
        $genre = $this->genreService->get($genreId);
        if (!$genre) {
            return back()->withErrors(['error' => 'Жанр не найден.']);
        }

        [$books, $params] = $this->genreService->books($filters);

        return view('genres.books', compact('books', 'params', 'filters', 'genre'));
    }
}
