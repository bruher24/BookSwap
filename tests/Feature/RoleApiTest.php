<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    private array $roleCreatePayload;

    private array $roleUpdatePayload;

    private array $roleWrongPayload = [
        'name' => 123,
    ];

    private User $admin;
    private User $user;
    private Role $role;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $adminRole = Role::factory()->createOne(['id' => Role::ADMIN_ROLE_ID, 'name' => 'admin']);
        $this->admin = User::factory()->createOne();
        $this->admin->roles()->attach($adminRole);

        $this->user = User::factory()->createOne();
        $this->role = Role::factory()->createOne();

        $this->roleCreatePayload = Role::factory()->raw();
        $this->roleUpdatePayload = Role::factory()->raw();
    }

    public function test_guest_cannot_index_role(): void
    {
        $response = $this->getJson('/api/v1/roles');
        $response->assertUnauthorized();
    }

    public function test_index_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->getJson('/api/v1/roles');
        $response->assertOk();
    }

    public function test_get_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->getJson("/api/v1/roles/{$this->role->id}");
        $response->assertOk();
    }

    public function test_not_found_get_role(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = $this->role->id + 1;
        $response = $this->getJson("/api/v1/roles/$newId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_role(): void
    {
        $response = $this->postJson('/api/v1/roles', $this->roleCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_role(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/roles', $this->roleCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/roles', $this->roleCreatePayload);
        $response->assertCreated();
    }

    public function test_validation_error_create_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/roles', $this->roleWrongPayload);
        $response->assertStatus(422);
    }

    public function test_guest_cannot_update_role(): void
    {
        $response = $this->patchJson("/api/v1/roles/{$this->role->id}", $this->roleUpdatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_update_role(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/roles/{$this->role->id}", $this->roleUpdatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_update_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/roles/{$this->role->id}", $this->roleUpdatePayload);
        $response->assertOk();
    }

    public function test_validation_error_update_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/roles/{$this->role->id}", $this->roleWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_role(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = $this->role->id + 1;
        $response = $this->patchJson("/api/v1/roles/$newId", $this->roleUpdatePayload);
        $response->assertNotFound();
    }

    public function test_guest_cannot_delete_role(): void
    {
        $response = $this->deleteJson("/api/v1/roles/{$this->role->id}");
        $response->assertUnauthorized();
    }

    public function test_user_cannot_delete_role(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/roles/{$this->role->id}");
        $response->assertForbidden();
    }

    public function test_admin_can_delete_role(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson("/api/v1/roles/{$this->role->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_role(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = $this->role->id + 1;
        $response = $this->deleteJson("/api/v1/roles/$newId");
        $response->assertAccepted();
    }
}
