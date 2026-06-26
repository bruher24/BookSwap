<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class TradeOfferResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'sender_id',
        'receiver_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [
        'books' => BookResource::class,
    ];
}
