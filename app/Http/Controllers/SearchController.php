<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultsResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $query = $validated['query'];

        $found = collect([
            'books' => Book::search($query)->get(),
            'authors' => Author::search($query)->get(),
            'total' => 0,
        ]);

        $total = $found->get('books')->count() + $found->get('authors')->count();
        $found->put('total', $total);

        $searchResults = new SearchResultsResource($found);

        return ResponseHelper::successResponse([
            'search_results' => $searchResults,
        ]);
    }
}
