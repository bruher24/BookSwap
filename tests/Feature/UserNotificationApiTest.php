<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class UserNotificationApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $other;
    private Notification $notification;
    private Notification $otherNotification;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        $this->notification = Notification::factory()->create(['user_id' => $this->user->id, 'seen' => false]);
        Notification::factory()->create(['user_id' => $this->user->id, 'seen' => false]);
        $this->otherNotification = Notification::factory()->create(['user_id' => $this->other->id, 'seen' => false]);
    }

    public function test_user_can_index_owned_notifications(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/me/notifications');
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonPath('data.0.attributes.user_id', $this->user->id);
    }

    public function test_guest_cannot_index_user_notifications(): void
    {
        $response = $this->getJson('/api/v1/me/notifications');
        $response->assertUnauthorized();
    }

    public function test_user_does_not_index_others_notifications(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/me/notifications');
        $response->assertOk();
        $response->assertJsonMissing(['id' => (string)$this->otherNotification->id]);
    }

    public function test_user_can_read_owned_notification(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/notifications/{$this->notification->id}/read");
        $response->assertAccepted();

        $this->assertDatabaseHas('notifications', [
            'id' => $this->notification->id,
            'seen' => true,
        ]);
    }

    public function test_user_cannot_read_others_notification(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->patchJson("/api/v1/notifications/{$this->otherNotification->id}/read");
        $response->assertForbidden();
    }
}
