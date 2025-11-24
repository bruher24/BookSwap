<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Cover extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'covers';
    public const string BASE_COVER_ID = '1';

    public $fillable = [
        'src',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Bookclass);
    }
}
