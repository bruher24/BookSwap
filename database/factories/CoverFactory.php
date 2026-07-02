<?php

namespace Database\Factories;

use App\Models\Cover;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

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
    #[Override]
    public function definition(): array
    {
        return [
            'src' => 'covers/' . $this->faker->uuid() . '.jpg',
            'user_id' => fn () => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->createOne()->id,
        ];
    }
}
