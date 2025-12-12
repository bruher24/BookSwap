<?php

namespace App\Models;

use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class TradeOffer extends Model implements Cacheable
{
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'tradeOffers';

    public $fillable = [
        'sender_id',
        'receiver_id',
        'date',
        'accepted'
    ];

    public function tradeOfferItems(): HasMany
    {
        return $this->hasMany(TradeOfferItem::class);
    }

    public function accept(): void
    {
        $this->accepted = true;
        $this->save();
    }

    public function reject(): void
    {
        $this->accepted = false;
        $this->save();
    }
}
