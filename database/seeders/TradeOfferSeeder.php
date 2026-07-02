<?php

namespace Database\Seeders;

use App\Enums\TradeOfferStatus;
use App\Models\Book;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Database\Seeder;

final class TradeOfferSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::query()->where('email', 'seller@seller.com')->firstOrFail();
        $buyer = User::query()->where('email', 'buyer@buyer.com')->firstOrFail();

        $tradeOffer = TradeOffer::query()->updateOrCreate(
            [
                'sender_id' => $seller->id,
                'receiver_id' => $buyer->id,
            ],
            [
                'status' => TradeOfferStatus::Pending,
            ]
        );

        $bookIds = Book::query()
            ->whereIn('isbn', [str_repeat('2', 13), str_repeat('3', 13)])
            ->pluck('id')
            ->all();

        Book::query()
            ->whereIn('id', $bookIds)
            ->update([
                'trade_offer_id' => $tradeOffer->id,
                'is_available' => false,
            ]);
    }
}
