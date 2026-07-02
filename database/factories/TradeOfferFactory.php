<?php

namespace Database\Factories;

use App\Enums\TradeOfferStatus;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<TradeOffer>
 */
final class TradeOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $senderId = User::query()->inRandomOrder()->value('id')
            ?? User::factory()->createOne()->id;
        $receiverId = User::query()
            ->whereKeyNot($senderId)
            ->inRandomOrder()
            ->value('id')
            ?? User::factory()->createOne()->id;

        return [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => TradeOfferStatus::Pending,
        ];
    }
}
