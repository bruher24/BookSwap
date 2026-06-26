<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class PhotoResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'user_id',
        'src',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [];
}
