<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

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
    #[Override]
    public function definition(): array
    {
        $firstId = $this->faker->unique()->numberBetween(1, 100000);
        $secondId = $this->faker->unique()->numberBetween(100001, 200000);

        return [
            'pair_key' => min($firstId, $secondId) . ':' . max($firstId, $secondId),
        ];
    }

    public function forUsers(User $firstUser, User $secondUser): ChatFactory
    {
        $firstId = min($firstUser->id, $secondUser->id);
        $secondId = max($firstUser->id, $secondUser->id);

        return $this->state(fn () => [
            'pair_key' => $firstId . ':' . $secondId,
        ])->afterCreating(function (Chat $chat) use ($firstId, $secondId): void {
            $chat->users()->syncWithoutDetaching([$firstId, $secondId]);
        });
    }
}
