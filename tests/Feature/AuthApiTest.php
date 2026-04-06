<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $otherUser;

    private array $registerPayload;
    private array $loginPayload;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->unverified()->createOne([
            'email' => 'another@user.com',
            'password' => bcrypt('1234'),
        ]);

        $this->loginPayload = [
            'email' => 'another@user.com',
            'password' => '1234',
        ];

        $this->registerPayload = [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => '1234',
        ];

        $this->otherUser = User::factory()->unverified()->createOne();
    }

    public function test_user_cannot_register(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/register', $this->registerPayload);
        $response->assertStatus(403);
    }

    public function test_guest_can_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', $this->registerPayload);
        $response->assertStatus(200);
    }

    public function test_guest_cannot_register_double(): void
    {
        $response = $this->postJson('/api/v1/auth/register', $this->registerPayload);
        $response->assertStatus(200);
    }

    public function test_user_cannot_login(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/login', $this->loginPayload);
        $response->assertStatus(403);
    }

    public function test_guest_can_login(): void
    {
        $response = $this->postJson('/api/v1/auth/login', $this->loginPayload);
        $response->assertStatus(200);
    }

    public function test_guest_cannot_refresh_token(): void
    {
        $response = $this->postJson('/api/v1/auth/refresh', ['email' => $this->user->email]);
        $response->assertStatus(401);
    }

    public function test_user_can_refresh_token_own(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/refresh', ['email' => $this->user->email]);
        $response->assertStatus(200);
    }

    public function test_user_cannot_refresh_token_others(): void
    {
        Sanctum::actingAs($this->otherUser);

        $response = $this->postJson('/api/v1/auth/refresh', ['email' => $this->user->email]);
        $response->assertStatus(403);
    }

    public function test_guest_cannot_logout(): void
    {
        $response = $this->postJson('/api/v1/auth/logout', ['email' => $this->otherUser->email]);
        $response->assertStatus(401);
    }

    public function test_user_cannot_logout_others(): void
    {
        Sanctum::actingAs($this->otherUser);

        $response = $this->postJson('/api/v1/auth/logout', ['email' => $this->user->email]);
        $response->assertStatus(403);
    }

    public function test_user_can_logout_own(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/auth/logout', ['email' => $this->user->email]);
        $response->assertStatus(200);
    }
}
