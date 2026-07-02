<?php

namespace Database\Factories;

use App\Enums\BookCondition;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Book>
 */
final class BookFactory extends Factory
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
            'name' => $this->faker->sentence(3),
            'user_id' => fn () => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->createOne()->id,
            'publishing_house' => $this->faker->company(),
            'publication_year' => $this->faker->year(),
            'isbn' => $this->faker->isbn13(),
            'page_count' => $this->faker->numberBetween(50, 1000),
            'condition' => $this->faker->randomElement(BookCondition::cases()),
            'book_type_id' => fn () => BookType::query()->inRandomOrder()->value('id')
                ?? BookType::factory()->createOne()->id,
            'cover_id' => fn (array $attributes) => Cover::query()
                ->where('user_id', $attributes['user_id'])
                ->inRandomOrder()
                ->value('id')
                ?? Cover::factory()->createOne(['user_id' => $attributes['user_id']])->id,
            'is_available' => true,
            'trade_offer_id' => null,
        ];
    }
}
