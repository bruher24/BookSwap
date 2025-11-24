<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DealItem extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'dealItems';

    public $fillable = [
        'deal_id',
        'book_id',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::final class);
    }
}
