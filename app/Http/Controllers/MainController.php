<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(): View
    {
        // TODO: only for testing
        $books = Book::all();
        return view('home', compact('books'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function search(Request $request): JsonResponse
    {
        // TODO: only for testing
        $query = $request->get('query');

        $found = [
            'books' => Book::search($query)->get(),
            'authors' => Author::search($query)->get(),
        ];

        foreach ($found as $key => $category) {
            if ($category->isEmpty()) {
                unset($found[$key]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $found,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}
