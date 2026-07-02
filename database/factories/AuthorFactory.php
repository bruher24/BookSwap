<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Author>
 */
final class AuthorFactory extends Factory
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
            'user_id' => fn () => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->createOne()->id,
            'lastname' => $this->faker->unique()->lastName(),
            'firstname' => $this->faker->firstName(),
            'patronymic' => $this->faker->optional(0.7)->lastName(),
            'birthdate' => $this->faker->dateTimeBetween('-100 years', '-14 years')->format('Y-m-d'),
        ];
    }
}
