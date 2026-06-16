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

        $response = $this->patchJson("/api/v1/users/{$this->user->id}", $this->userUpdatePayload);
        $response->assertOk();
    }

    public function test_user_cannot_update_other_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->target->id}", $this->userUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_delete_itself(): void
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

    public function test_user_can_rate_another_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->target->id}/rate", [
            'rate' => 5,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('ratings', [
            'user_id' => $this->target->id,
            'rater_id' => $this->user->id,
            'rate' => 5,
        ]);
        $this->assertEquals(5.0, $this->target->refresh()->rating);
    }

    public function test_user_cannot_rate_itself(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->user->id}/rate", [
            'rate' => 5,
        ]);

        $response->assertForbidden();
    }

    public function test_rate_requires_valid_value(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->target->id}/rate", [
            'rate' => 6,
        ]);

        $response->assertStatus(422);
    }
}
