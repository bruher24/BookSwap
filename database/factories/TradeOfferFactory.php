<?php

namespace Database\Factories;

use App\Models\TradeOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'sender_id' => $this->faker->numberBetween(1, 100),
            'receiver_id' => $this->faker->numberBetween(1, 100),
            'date' => $this->faker->dateTime()->format('Y-m-d H:i:s'),
            'accepted' => $this->faker->boolean(),
        ];
    }
}
