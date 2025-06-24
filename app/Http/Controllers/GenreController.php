<?php

namespace App\Http\Controllers;

use App\Services\GenreService;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    private GenreService  $genreService;
    public function __construct()
    {
        $this->genreService = new GenreService();
    }

    public function index()
    {
        $genres = $this->genreService->getAll();
        return view('genres.index', compact('genres'));
    }
}
