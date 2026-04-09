<?php

namespace App\Models;

use App\Enums\TradeOfferStatus;
use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidation;
use Database\Factories\TradeOfferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class TradeOffer extends Model implements Cacheable
{
    /** @use HasFactory<TradeOfferFactory> */
    use HasFactory;
    use SoftDeletes;
    use CacheInvalidation;

    public const string CACHE_KEY = 'tradeOffers';

    public $fillable = [
        'sender_id',
        'receiver_id',
        'status'
    ];

    protected $casts = [
        'status' => TradeOfferStatus::class,
    ];

    public function isUserBelongs(User $user): bool
    {
        $tradeOfferUsers = [
            $this->sender_id,
            $this->receiver_id
        ];
        return in_array($user->id, $tradeOfferUsers);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TradeOfferItem::class);
    }
}
