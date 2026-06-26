<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultsResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $query = $validated['query'];

        $foundBooks = Book::search($query)->get();
        $foundAuthors = Author::search($query)->get();

        $total = $foundBooks->count() + $foundAuthors->count();

        $searchResults = new SearchResultsResource([
            'books' => $foundBooks,
            'authors' => $foundAuthors,
            'total' => $total,
        ]);

        return $searchResults->response()->setStatusCode(Response::HTTP_OK);
    }
}
