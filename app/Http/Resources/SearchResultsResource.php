<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;
use Override;

final class SearchResultsResource extends JsonApiResource
{
    #[Override]
    public function toId(Request $request): string
    {
        return 'search-results';
    }

    #[Override]
    public function toType(Request $request): string
    {
        return 'search_results';
    }

    /**
     * The resource's attributes.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toAttributes(Request $request): array
    {
        return [
            'books' => BookResource::collection($this->resource['books'] ?? [])->resolve($request),
            'authors' => AuthorResource::collection($this->resource['authors'] ?? [])->resolve($request),
            'total' => $this->resource['total'] ?? 0,
        ];
    }

    /**
     * The resource's relationships.
     */
    public array $relationships = [];
}
