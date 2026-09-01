<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    private array $authorCreatePayload;
    private array $authorUpdatePayload;
    private array $authorWrongPayload = [
        'lastname' => '!?.,',
        'firstname' => 452,
        'patronymic' => false,
        'birthdate' => 123
    ];
    private int $notFoundId = 999999;
    private User $owner;
    private User $other;
    private Author $author;
    private Author $otherAuthor;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->createOne();
        $this->other = User::factory()->createOne();
        $this->author = Author::factory()->createOne(['user_id' => $this->owner->id]);
        $this->otherAuthor = Author::factory()->createOne(['user_id' => $this->other->id]);

        $this->authorCreatePayload = Author::factory()->raw();
        $this->authorUpdatePayload = Author::factory()->raw();
    }

    public function test_index_author(): void
    {
        $response = $this->getJson('/api/v1/authors');
        $response->assertOk();
    }

    public function test_get_author(): void
    {
        $response = $this->getJson("/api/v1/authors/{$this->author->id}");
        $response->assertOk();
    }

    public function test_not_found_get_author(): void
    {
        $response = $this->getJson("/api/v1/authors/$this->notFoundId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_author(): void
    {
        $response = $this->postJson('/api/v1/authors', $this->authorCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_author(): void
    {
        Sanctum::actingAs($this->owner);
        $this->authorCreatePayload['user_id'] = $this->owner->id;
        $response = $this->postJson('/api/v1/authors', $this->authorCreatePayload);
        $response->assertCreated();
    }

    public function test_validation_error_create_author(): void
    {
        Sanctum::actingAs($this->owner);
        $this->authorWrongPayload['user_id'] = $this->owner->id;
        $response = $this->postJson('/api/v1/authors', $this->authorWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_others_author(): void
    {
        Sanctum::actingAs($this->other);
        $this->authorUpdatePayload['user_id'] = $this->other->id;
        $response = $this->patchJson("/api/v1/authors/{$this->author->id}", $this->authorUpdatePayload);
        $response->assertForbidden();
    }

    public function test_guest_cannot_update_author(): void
    {
        $response = $this->patchJson("/api/v1/authors/{$this->author->id}", $this->authorUpdatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_update_owned_author(): void
    {
        Sanctum::actingAs($this->owner);

        $this->authorUpdatePayload['user_id'] = $this->owner->id;
        $response = $this->patchJson("/api/v1/authors/{$this->author->id}", $this->authorUpdatePayload);
        $response->assertOk();
    }

    public function test_validation_error_update_author(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->patchJson("/api/v1/authors/{$this->author->id}", $this->authorWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_author(): void
    {
        Sanctum::actingAs($this->owner);

        $this->authorUpdatePayload['user_id'] = $this->owner->id;
        $response = $this->patchJson("/api/v1/authors/$this->notFoundId", $this->authorUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_others_author(): void
    {
        Sanctum::actingAs($this->other);
        $response = $this->deleteJson("/api/v1/authors/{$this->author->id}");
        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_author(): void
    {
        $response = $this->deleteJson("/api/v1/authors/{$this->author->id}");
        $response->assertUnauthorized();
    }

    public function test_user_can_delete_owned_author(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->deleteJson("/api/v1/authors/{$this->author->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_author(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->deleteJson("/api/v1/authors/$this->notFoundId");
        $response->assertAccepted();
    }

    public function test_user_can_index_owned_authors(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/me/authors');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', (string)$this->author->id);
    }

    public function test_guest_cannot_index_user_authors(): void
    {
        $response = $this->getJson('/api/v1/me/authors');
        $response->assertUnauthorized();
    }

    public function test_user_does_not_index_others_authors(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/me/authors');
        $response->assertOk();
        $response->assertJsonMissing(['id' => (string)$this->otherAuthor->id]);
    }
}
