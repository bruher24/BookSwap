<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class BookResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'name',
        'user_id',
        'publishing_house',
        'publication_year',
        'isbn',
        'page_count',
        'condition',
        'book_type_id',
        'cover_id',
        'is_available',
        'trade_offer_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [
        'genres' => GenreResource::class,
        'authors' => AuthorResource::class,
        'cover' => CoverResource::class,
        'user' => UserResource::class,
        'book_type' => BookTypeResource::class,
        'trade_offer' => TradeOfferResource::class,
    ];
}
