<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    private BookService $bookService;

    public function __construct() {
        $this->bookService = new BookService();
    }

    public function index(): View
    {
        $books = $this->bookService->getAll();
        return view('books.index', compact('books'));
    }

    public function create(){

    }

    public function store(Request $request){

    }

    public function show(Book $book){

    }

    public function edit(Book $book){

    }

    public function update(Request $request, Book $book){

    }

    public function destroy(Book $book){

    }
}
