<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Photo extends Model
{
    use SoftDeletes, CacheInvalidation;

    const string CACHE_KEY = 'photos';

    public $fillable = [
        'src',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
