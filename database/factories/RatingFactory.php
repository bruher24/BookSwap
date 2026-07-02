<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Rating>
 */
final class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $user = User::factory()->createOne();
        $rater = User::factory()->createOne();

        return [
            'user_id' => $user->id,
            'rater_id' => $rater->id,
            'rate' => $this->faker->numberBetween(0, 5),
        ];
    }
}
