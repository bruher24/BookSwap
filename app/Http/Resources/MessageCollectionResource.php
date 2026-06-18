<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Override;

final class MessageCollectionResource extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = MessageResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        if (!isset($this->collection)) {
            return [];
        }

        return $this->collection
            ->groupBy(fn ($item): string => $item->created_at->format('Y-m-d'))
            ->map(fn ($group): array => MessageResource::collection($group)->resolve())
            ->toArray();
    }
}
