<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Photo extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'photos';
    public const string BASE_PHOTO_ID = '1';

    public $fillable = [
        'src',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(Userclass);
    }
}
