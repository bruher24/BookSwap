<?php

namespace App\Models;

use App\Enums\TradeOfferStatusEnum;
use App\Interfaces\Cacheable;
use App\Traits\CacheInvalidationTrait;
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
    use CacheInvalidationTrait;

    public const string CACHE_KEY = 'tradeOffers';

    public $fillable = [
        'sender_id',
        'receiver_id',
        'status'
    ];

    protected $casts = [
        'status' => TradeOfferStatusEnum::class,
    ];

    public function isUserBelongs(User $user): bool
    {
        return in_array($user->id, [
            $this->sender_id,
            $this->receiver_id
        ]);
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
