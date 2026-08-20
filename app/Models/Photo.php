<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidationTrait;
use Database\Factories\PhotoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Photo extends Model implements Cacheable
{
    /** @use HasFactory<PhotoFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidationTrait;

    public const string CACHE_KEY = 'photos';
    public const int BASE_PHOTO_ID = 1;

    public $fillable = [
        'src',
        'user_id'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
