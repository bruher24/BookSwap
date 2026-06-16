<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class SettingApiTest extends TestCase
{
    use RefreshDatabase;

    private array $settingCreatePayload;

    private array $settingUpdatePayload;

    private array $settingWrongPayload = [
        'name' => 1,
        'label' => 2,
        'available_values' => 'bad',
    ];

    private User $admin;
    private User $user;
    private Setting $setting;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->createOne(['name' => 'admin']);
        $this->admin = User::factory()->createOne();
        $this->admin->roles()->attach($role);

        $this->user = User::factory()->createOne();
        $this->setting = Setting::factory()->createOne();

        $this->settingCreatePayload = Setting::factory()->raw();
        $this->settingUpdatePayload = Setting::factory()->raw();
    }

    public function test_index_setting(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->getJson('/api/v1/settings');
        $response->assertOk();
    }

    public function test_get_setting(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->getJson("/api/v1/settings/{$this->setting->id}");
        $response->assertOk();
    }

    public function test_not_found_get_setting(): void
    {
        Sanctum::actingAs($this->user);
        $newId = (int)$this->setting->id + 1;
        $response = $this->getJson("/api/v1/settings/$newId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_setting(): void
    {
        $response = $this->postJson('/api/v1/settings', $this->settingCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_setting(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/settings', $this->settingCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/settings', $this->settingCreatePayload);
        $response->assertCreated();
    }

    public function test_validation_error_create_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/settings', $this->settingWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_setting(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/settings/{$this->setting->id}", $this->settingUpdatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_update_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/settings/{$this->setting->id}", $this->settingUpdatePayload);
        $response->assertOk();
    }

    public function test_validation_error_update_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/settings/{$this->setting->id}", $this->settingWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = (int)$this->setting->id + 1;
        $response = $this->patchJson("/api/v1/settings/$newId", $this->settingUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_setting(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/settings/{$this->setting->id}");
        $response->assertForbidden();
    }

    public function test_admin_can_delete_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson("/api/v1/settings/{$this->setting->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_setting(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = (int)$this->setting->id + 1;
        $response = $this->deleteJson("/api/v1/settings/$newId");
        $response->assertAccepted();
    }
}
