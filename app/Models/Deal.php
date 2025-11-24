<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Deal extends Model
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'deals';

    public $fillable = [
        'seller_id',
        'buyer_id',
        'date',
    ];

    public function dealItems(): HasMany
    {
        return $this->hasMany(DealItem::class);
    }
}
