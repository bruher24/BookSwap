<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResultsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'books' => BookResource::collection($this->get('books')),
            'authors' => AuthorResource::collection($this->get('authors')),
            'total' => (int)$this->get('total')
        ];
    }
}
