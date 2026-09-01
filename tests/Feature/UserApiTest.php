<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
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
    private Book $favoriteBook;
    private Book $otherFavoriteBook;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->unverified()->createOne();
        $adminRole = Role::factory()->createOne(['name' => 'admin']);
        Role::factory()->createOne(['name' => 'user']);
        $this->admin->roles()->attach($adminRole);
        $this->user = User::factory()->unverified()->createOne();
        $this->target = User::factory()->unverified()->createOne();
        $this->userCreatePayload = User::factory()->unverified()->raw();
        $this->userUpdatePayload = User::factory()->unverified()->raw();
        unset($this->userUpdatePayload['password']);

        $author = Author::factory()->createOne(['user_id' => $this->user->id]);
        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne(['user_id' => $this->user->id]);
        $this->favoriteBook = Book::factory()->createOne([
            'user_id' => $this->user->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);
        $this->favoriteBook->authors()->attach($author);

        $cover = Cover::factory()->createOne(['user_id' => $this->target->id]);
        $this->otherFavoriteBook = Book::factory()->createOne([
            'user_id' => $this->target->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);
        $this->otherFavoriteBook->authors()->attach($author);
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

    public function test_guest_cannot_index_user(): void
    {
        $response = $this->getJson('/api/v1/users');
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_user(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson('/api/v1/users', $this->userCreatePayload);
        $response->assertForbidden();
    }

    public function test_guest_cannot_create_user(): void
    {
        $response = $this->postJson('/api/v1/users', $this->userCreatePayload);
        $response->assertUnauthorized();
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

    public function test_guest_can_get_user(): void
    {
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

    public function test_guest_cannot_update_user(): void
    {
        $response = $this->patchJson("/api/v1/users/{$this->target->id}", $this->userUpdatePayload);
        $response->assertUnauthorized();
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

    public function test_guest_cannot_delete_user(): void
    {
        $response = $this->deleteJson("/api/v1/users/{$this->target->id}");
        $response->assertUnauthorized();
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

    public function test_guest_cannot_rate_user(): void
    {
        $response = $this->patchJson("/api/v1/users/{$this->target->id}/rate", [
            'rate' => 5,
        ]);

        $response->assertUnauthorized();
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

    public function test_user_can_like_book(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson("/api/v1/me/like/{$this->otherFavoriteBook->id}");
        $response->assertOk();
    }

    public function test_user_can_index_owned_favorites(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/me/favorites');
        $response->assertOk();
    }

    public function test_guest_cannot_index_user_favorites(): void
    {
        $response = $this->getJson('/api/v1/me/favorites');
        $response->assertUnauthorized();
    }

    public function test_user_does_not_index_others_favorites(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson('/api/v1/me/favorites');
        $response->assertOk();
        $response->assertJsonMissing(['id' => (string)$this->favoriteBook->id]);
    }

    public function test_user_can_dislike_book(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/me/dislike/{$this->otherFavoriteBook->id}");
        $response->assertAccepted();
    }
}
