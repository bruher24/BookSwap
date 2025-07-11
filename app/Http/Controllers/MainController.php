<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Transport\Exception\NoNodeAvailableException;
use JeroenG\Explorer\Domain\Syntax\Matching;
use JeroenG\Explorer\Domain\Syntax\Nested;

class MainController extends Controller
{
    public function index(): View
    {
        // TODO: исправить

        $search = Book::search('александр')->get();
        dd($search);

        $books = Book::all();
        return view('home', compact('books'));
    }

    public function about(): View
    {
        return view('about');
    }
}
