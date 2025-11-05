<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\UserService;
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
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
            'remember_token' => '12345',
        ];

        $response = $this->post('api/v1/auth/register', $data);
        $response->assertJsonFragment([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'systemMessage' => 'Успешная регистрация'
        ]);
        $this->assertDatabaseHas('users', ['email' => 'john@doe.com']);
    }

    public function test_update_user(): void
    {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            [$user->email]
        );
        $response = $this->get('api/v1/users/' . $user->id);
        dump($response);
    }
}
