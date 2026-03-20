<?php

namespace Database\Factories;

use App\Models\Cover;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cover>
 */
final class CoverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'src' => $this->faker->imageUrl(),
            'user_id' => 1,
        ];
    }
}
