<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Author>
 * @psalm-suppress UnusedClass
 * @psalm-suppress PropertyNotSetInConstructor
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
            'user_id' => 1,
            'lastname' => $this->faker->lastName(),
            'firstname' => $this->faker->firstName(),
            'patronymic' => $this->faker->lastName(),
            'birthdate' => $this->faker->dateTimeBetween('-2000 years', '-14 years')->format('Y-m-d'),
        ];
    }
}
