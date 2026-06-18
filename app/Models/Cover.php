<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Database\Factories\CoverFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Cover extends Model implements Cacheable
{
    /** @use HasFactory<CoverFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'covers';
    public const int BASE_COVER_ID = 1;

    public $fillable = [
        'src',
        'user_id'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
