<?php

namespace Database\Factories;

use App\Models\Filter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Filter>
 */
final class FilterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'by_fields' => $this->faker->word() . ',' . $this->faker->word(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
