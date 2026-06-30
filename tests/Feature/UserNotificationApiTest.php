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

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        Notification::factory()->count(2)->create(['user_id' => $this->user->id]);
        Notification::factory()->create(['user_id' => $this->other->id]);
    }

    public function test_user_can_index_owned_notifications(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/v1/notifications/by_user/{$this->user->id}");
        $response->assertOk();
    }

    public function test_guest_cannot_index_user_notifications(): void
    {
        $response = $this->getJson("/api/v1/notifications/by_user/{$this->user->id}");
        $response->assertUnauthorized();
    }

    public function test_user_cannot_index_others_notifications(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/notifications/by_user/{$this->user->id}");
        $response->assertForbidden();
    }
}
