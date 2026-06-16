<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class UserSettingApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $other;
    private Setting $setting;
    private array $validSettingsPayload;
    private array $invalidSettingsPayload;
    private array $emptySettingsPayload;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
        $this->other = User::factory()->createOne();
        $this->setting = Setting::factory()->createOne();

        $this->validSettingsPayload = [
            'value' => 'on',
        ];

        $this->invalidSettingsPayload = [
            'value' => 'invalid',
        ];

        $this->emptySettingsPayload = [];
    }

    public function test_user_can_index_owned_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/v1/users/{$this->user->id}/settings");
        $response->assertOk();
    }

    public function test_user_cannot_index_others_settings(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/users/{$this->user->id}/settings");
        $response->assertForbidden();
    }

    public function test_user_can_update_owned_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->user->id}/settings/{$this->setting->id}", $this->validSettingsPayload);
        $response->assertOk();
    }

    public function test_user_cannot_update_others_settings(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->patchJson("/api/v1/users/{$this->user->id}/settings/{$this->setting->id}", $this->validSettingsPayload);
        $response->assertForbidden();
    }

    public function test_validation_error_update_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/users/{$this->user->id}/settings/{$this->setting->id}", $this->emptySettingsPayload);
        $response->assertStatus(422);
    }

    public function test_invalid_settings_update_returns_validation_error(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/users/{$this->user->id}/settings/{$this->setting->id}", $this->invalidSettingsPayload);
        $response->assertStatus(422);
    }
}
