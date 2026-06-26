<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class RoleResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [
        'users' => UserResource::class,
    ];
}
