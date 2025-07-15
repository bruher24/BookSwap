<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Deal extends Model
{
    use SoftDeletes;

    public $fillable = [
        'seller_id',
        'buyer_id',
        'date',
    ];

    protected $with = [
        'dealItems',
    ];

    public function dealItems(): HasMany
    {
        return $this->hasMany(DealItem::class);
    }
}
