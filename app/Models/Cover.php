<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cover extends Model
{
    use SoftDeletes, CacheInvalidation;

    const string CACHE_KEY = 'covers';

    private string $baseCoverPath = 'storage/app/public/cover.png';

    public static int $baseCoverId = 1;

    public $fillable = [
        'src'
    ];

    public function book(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
