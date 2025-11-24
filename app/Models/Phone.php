<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Phone extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'phones';

    public $fillable = [
        'number',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::final class);
    }
}
