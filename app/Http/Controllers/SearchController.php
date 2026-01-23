<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultsResource;
use App\Http\Resources\SuccessResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Resources\Json\JsonResource;

final class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResource
    {
        $validated = $request->validated();
        $query = (string)$validated['query'];

        $foundBooks = Book::search($query)->get();
        $foundAuthors = Author::search($query)->get();
        $total = $foundBooks->count() + $foundAuthors->count();

        $searchResults = new SearchResultsResource([
            'books' => $foundBooks,
            'authors' => $foundAuthors,
            'total' => $total,
        ]);
        $data = ['search_results' => $searchResults];

        return new SuccessResource(['data' => $data]);
    }
}
