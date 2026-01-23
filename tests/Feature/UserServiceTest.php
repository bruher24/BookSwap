<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class UserServiceTest extends TestCase
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
            'statusCode' => 200
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@doe.com']);
    }

    public function test_update_user_success(): void
    {
        Event::fake();
        $user = User::factory()->createOne(['password' => '1234']);
        Sanctum::actingAs(
            $user,
            [$user->email, 'admin']
        );

        $this->withHeader('Accept', 'application/json');
        $response = $this->put('api/v1/users/' . $user->id, [
            'name' => 'new_name',
            'email' => 'new_email@email.com',
            'old_password' => '1234',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
            'phone_number' => '89998887766',
        ]);

        $response->assertStatus(200);
    }

    public function test_update_user_validation(): void
    {
        Event::fake();
        $user = User::factory()->createOne(['password' => '1234']);
        Sanctum::actingAs(
            $user,
            [$user->email, 'admin']
        );

        $this->withHeader('Accept', 'application/json');
        $response = $this->put('api/v1/users/' . $user->id, [
            'name' => '',
            'email' => 'new_email.com',
            'old_password' => '666',
            'password' => 767,
            'password_confirmation' => 'new_password',
            'phone_number' => 'asdasd',
        ]);

        $response->assertStatus(422);
    }
}
