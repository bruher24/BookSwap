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
            'user_id' => 9999,
            'lastname' => fake()->lastName(),
            'firstname' => fake()->firstName(),
            'patronymic' => fake()->lastName(),
            'birthdate' => fake()->date(),
        ];
    }
}
