<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidationTrait;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Role extends Model implements Cacheable
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidationTrait;

    public const string CACHE_KEY = 'roles';

    public const int ADMIN_ROLE_ID = 1;

    public const int USER_ROLE_ID = 2;

    public $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
