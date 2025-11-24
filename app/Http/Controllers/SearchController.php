<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultsResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

final class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $query = $validated['query'];
        $total = 0;

        $found = collect([
            'books' => Book::search($query)->get(),
            'authors' => Author::search($query)->get(),
            'total' => $total,
        ]);

        $foundBooks = $found->get('books');
        $foundAuthors = $found->get('authors');

        if ($foundBooks instanceof Collection) {
            $total += $foundBooks->count();
        }

        if ($foundAuthors instanceof Collection) {
            $total += $foundAuthors->count();
        }

        /**
         * @psalm-suppress ArgumentTypeCoercion
         */
        $found->put('total', $total);

        $searchResults = new SearchResultsResource($found);

        return ResponseHelper::successResponse([
            'search_results' => $searchResults,
        ]);
    }
}
