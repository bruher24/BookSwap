<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;
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

    public function search(Request $request): View
    {
        $search = $request->search;
        $found = [
            Book::search($search)->get(),
            Author::search($search)->get(),
        ];
        return view('search', compact('found'));
    }
}
