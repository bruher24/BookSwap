<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function __construct() {

    }

    public function getAllBooks(){
        $books = Book::all();
        return $books;
    }
}