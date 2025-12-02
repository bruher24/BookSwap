<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\SearchResultsResource;
use App\Http\Resources\SuccessResource;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

final class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): JsonResource
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
        $data = ['search_results' => $searchResults];

        return new SuccessResource(['data' => $data]);
    }
}
