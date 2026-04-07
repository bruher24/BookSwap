<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class UserApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private User $target;
    private array $userCreatePayload;
    private array $userUpdatePayload;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->unverified()->createOne();
        $adminRole = Role::factory()->createOne(['name' => 'admin']);
        $this->admin->roles()->attach($adminRole);
        $this->user = User::factory()->unverified()->createOne();
        $this->target = User::factory()->unverified()->createOne();
        $this->userCreatePayload = User::factory()->unverified()->raw();
        $this->userUpdatePayload = User::factory()->unverified()->raw();
        unset($this->userUpdatePayload['password']);
    }

    public function test_admin_can_index_user(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/users');
        $response->assertOk();
    }

    public function test_user_cannot_index_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/users');
        $response->assertForbidden();
    }

    public function test_user_cannot_create_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/users', $this->userCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_user(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/v1/users', $this->userCreatePayload);
        $response->assertCreated();
    }

    public function test_user_can_get_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/v1/users/{$this->target->id}");
        $response->assertOk();
    }

    public function test_user_can_update_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->putJson("/api/v1/users/{$this->user->id}", $this->userUpdatePayload);
        $response->assertOk();
    }

    public function test_user_cannot_update_other_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->putJson("/api/v1/users/{$this->target->id}", $this->userUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_delete_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/users/{$this->user->id}");
        $response->assertAccepted();
    }

    public function test_user_cannot_delete_other_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/users/{$this->target->id}");
        $response->assertForbidden();
    }
}
