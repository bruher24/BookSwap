<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_user(): void
    {
        Event::fake();

        $data = [
            'name' => 'johndoe',
            'email' => 'john@doe.com',
            'password' => 'password'
        ];

        $response = $this->post('api/v1/auth/register', $data);
        $response->assertJsonFragment([
            'name' => 'johndoe',
            'email' => 'john@doe.com',
            'systemMessage' => 'Успешная регистрация'
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@doe.com']);
    }

    public function test_update_user(): void
    {
        Sanctum::actingAs(
            $user = User::factory()->createOne(),
            [$user->email]
        );

        $response = $this->put('api/v1/users/' . $user->id, [
            'name' => 'johndoe',
            'email' => 'john@doe.com',
            'password' => 'password'
        ]);
        $response->assertStatus(200);
        dump($response);
    }
}
