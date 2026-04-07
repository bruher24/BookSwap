<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class ChatApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $firstUser;
    private User $other;
    private Chat $chat;
    private array $firstChatPayload;
    private array $secondChatPayload;
    private array $messagePayload;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->createOne();
        $adminRole = Role::factory()->createOne(['name' => 'admin']);
        $this->admin->roles()->attach($adminRole);

        $this->firstUser = User::factory()->createOne();
        $secondUser = User::factory()->createOne();
        $thirdUser = User::factory()->createOne();
        $fourthUser = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        $this->firstChatPayload = [
            'first_user_id' => $this->firstUser->id,
            'second_user_id' => $secondUser->id,
        ];
        $this->secondChatPayload = [
            'first_user_id' => $thirdUser->id,
            'second_user_id' => $fourthUser->id,
        ];
        $this->chat = Chat::factory()->createOne($this->firstChatPayload);

        $this->messagePayload = [
            'sender_id' => $this->firstUser->id,
            'body' => 'Привет!',
        ];
    }

    public function test_admin_can_index_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/chats');
        $response->assertOk();
    }

    public function test_user_cannot_index_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson('/api/v1/chats');
        $response->assertForbidden();
    }

    public function test_user_cannot_create_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->postJson('/api/v1/chats', $this->secondChatPayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/v1/chats', $this->secondChatPayload);
        $response->assertCreated();
    }

    public function test_cannot_create_double_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->postJson('/api/v1/chats', $this->firstChatPayload);
        $response->assertStatus(422);
    }

    public function test_admin_can_get_any_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/chats/' . $this->chat->id);
        $response->assertOk();
    }

    public function test_user_can_get_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $response = $this->getJson('/api/v1/chats/' . $this->chat->id);
        $response->assertOk();
    }

    public function test_user_cannot_get_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson('/api/v1/chats/' . $this->chat->id);
        $response->assertForbidden();
    }

    public function test_user_cannot_update_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $payload = $this->firstChatPayload;
        $payload['blocked_by'] = 'first';

        $response = $this->putJson('/api/v1/chats/' . $this->chat->id, $payload);
        $response->assertForbidden();
    }

    public function test_user_cannot_update_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $payload = $this->firstChatPayload;
        $payload['blocked_by'] = 'second';

        $response = $this->putJson('/api/v1/chats/' . $this->chat->id, $payload);
        $response->assertForbidden();
    }

    public function test_admin_can_update_any_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $payload = $this->firstChatPayload;
        $payload['blocked_by'] = 'second';

        $response = $this->putJson('/api/v1/chats/' . $this->chat->id, $payload);
        $response->assertOk();
    }

    public function test_user_cannot_delete_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $response = $this->deleteJson('/api/v1/chats/' . $this->chat->id);
        $response->assertForbidden();
    }

    public function test_user_cannot_delete_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson('/api/v1/chats/' . $this->chat->id);
        $response->assertForbidden();
    }

    public function test_admin_can_delete_any_chat(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson('/api/v1/chats/' . $this->chat->id);
        $response->assertAccepted();
    }

    public function test_user_can_by_user_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $response = $this->getJson("/api/v1/chats/by_user/{$this->firstUser->id}");
        $response->assertOk();
    }

    public function test_user_cannot_by_user_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/chats/by_user/{$this->firstUser->id}");
        $response->assertForbidden();
    }

    public function test_user_can_get_messages_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $response = $this->getJson("/api/v1/chats/{$this->chat->id}/messages");
        $response->assertOk();
    }

    public function test_user_cannot_get_messages_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/chats/{$this->chat->id}/messages");
        $response->assertForbidden();
    }

    public function test_user_can_send_message_owned_chat(): void
    {
        Sanctum::actingAs($this->firstUser);

        $response = $this->postJson("/api/v1/chats/{$this->chat->id}/messages", $this->messagePayload);
        $response->assertCreated();
    }

    public function test_user_cannot_send_message_others_chat(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->postJson("/api/v1/chats/{$this->chat->id}/messages", $this->messagePayload);
        $response->assertForbidden();
    }
}
