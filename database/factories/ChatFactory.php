<?php

namespace Database\Factories;

use App\Models\Chat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chat>
 */
final class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_user_id' => $this->faker->numberBetween(1, 100),
            'second_user_id' => $this->faker->numberBetween(1, 100),
            'blocked_by' => null,
        ];
    }
}
