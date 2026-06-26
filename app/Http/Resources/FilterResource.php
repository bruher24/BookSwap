<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class FilterResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'name',
        'by_fields',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [];
}
