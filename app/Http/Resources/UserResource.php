<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\JsonApi\JsonApiResource;

final class UserResource extends JsonApiResource
{
    /**
     * The resource's attributes.
     */
    public array $attributes = [
        'name',
        'email',
        'city',
        'rating',
        'mainRole',
        'registeredDiff',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The resource's relationships.
     */
    public array $relationships = [
        'roles' => RoleResource::class,
        'books' => BookResource::class,
        'photo' => PhotoResource::class,
        'settings' => SettingResource::class,
        'chats' => ChatResource::class,
        'notifications' => NotificationResource::class,
        'ratings',
        'authors' => AuthorResource::class,
    ];
}
