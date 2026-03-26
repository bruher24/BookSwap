<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->unverified()->createOne();
    }

    public function test_guest_can_register(): void
    {
        $payload = [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => '1234',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);
        $response->assertStatus(200);
    }

    public function test_user_cannot_register(): void
    {
        Sanctum::actingAs($this->user);

        $payload = [
            'name' => 'Another User',
            'email' => 'another@user.com',
            'password' => '1234',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);
        $response->assertStatus(200);
    }
}
