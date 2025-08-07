<?php

namespace App\Models;

use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes, CacheInvalidation;

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
