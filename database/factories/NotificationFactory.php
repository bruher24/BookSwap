<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Notification>
 */
final class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => $this->faker->numberBetween(1, 100),
            'seen' => $this->faker->boolean,
        ];
    }
}
