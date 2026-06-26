<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class NotificationResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'subject',
        'body',
        'user_id',
        'seen',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [];
}
