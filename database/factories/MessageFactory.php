<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Override;

/**
 * @extends Factory<Message>
 */
final class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function definition(): array
    {
        $chat = Chat::query()->has('users')->inRandomOrder()->first();

        if (!$chat instanceof Chat) {
            $firstUser = User::factory()->createOne();
            $secondUser = User::factory()->createOne();
            $chat = Chat::factory()->forUsers($firstUser, $secondUser)->createOne();
        }

        return [
            'chat_id' => $chat->id,
            'sender_id' => $chat->users()->inRandomOrder()->value('users.id'),
            'subject' => $this->faker->optional()->sentence(3),
            'body' => $this->faker->paragraph(),
            'seen' => $this->faker->boolean(),
        ];
    }
}
