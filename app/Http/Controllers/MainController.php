<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        $books = Book::all();
        return view('home', compact('books'));
    }

    public function about(): View
    {
        return view('about');
    }
}
