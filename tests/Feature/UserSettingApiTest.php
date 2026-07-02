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
    private Setting $otherSetting;
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
        $this->otherSetting = Setting::factory()->createOne();

        $this->user->settings()->attach($this->setting->id, ['value' => 'off']);
        $this->other->settings()->attach($this->otherSetting->id, ['value' => 'off']);

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

        $response = $this->getJson('/api/v1/me/settings');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', (string)$this->setting->id);
    }

    public function test_guest_cannot_index_user_settings(): void
    {
        $response = $this->getJson('/api/v1/me/settings');
        $response->assertUnauthorized();
    }

    public function test_user_does_not_index_others_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/me/settings');
        $response->assertOk();
        $response->assertJsonMissing(['id' => (string)$this->otherSetting->id]);
    }

    public function test_user_can_update_owned_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/settings/update_for_user/{$this->setting->id}", $this->validSettingsPayload);
        $response->assertAccepted();

        $this->assertDatabaseHas('setting_user', [
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id,
            'value' => 'on',
        ]);
    }

    public function test_guest_cannot_update_user_settings(): void
    {
        $response = $this->patchJson("/api/v1/settings/update_for_user/{$this->setting->id}", $this->validSettingsPayload);
        $response->assertUnauthorized();
    }

    public function test_user_update_affects_only_own_settings(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->patchJson("/api/v1/settings/update_for_user/{$this->setting->id}", $this->validSettingsPayload);
        $response->assertAccepted();

        $this->assertDatabaseMissing('setting_user', [
            'user_id' => $this->user->id,
            'setting_id' => $this->setting->id,
            'value' => 'on',
        ]);

        $this->assertDatabaseHas('setting_user', [
            'user_id' => $this->other->id,
            'setting_id' => $this->setting->id,
            'value' => 'on',
        ]);
    }

    public function test_validation_error_update_settings(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/settings/update_for_user/{$this->setting->id}", $this->emptySettingsPayload);
        $response->assertStatus(422);
    }

    public function test_invalid_settings_update_returns_validation_error(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/settings/update_for_user/{$this->setting->id}", $this->invalidSettingsPayload);
        $response->assertStatus(422);
    }
}
