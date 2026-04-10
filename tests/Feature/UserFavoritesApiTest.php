<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\User;
use App\Models\UsersFavoriteBooks;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class UserFavoritesApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $other;
    private Book $favoriteBook;
    private Book $newBook;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne(['user_id' => $this->user->id]);

        $this->favoriteBook = Book::factory()->createOne([
            'user_id' => $this->user->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $this->newBook = Book::factory()->createOne([
            'user_id' => $this->user->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        UsersFavoriteBooks::create([
            'user_id' => $this->user->id,
            'book_id' => $this->favoriteBook->id,
        ]);
    }

    public function test_user_can_index_owned_favorites(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->getJson("/api/v1/users/{$this->user->id}/favorites");
        $response->assertOk();
    }

    public function test_user_cannot_index_others_favorites(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/users/{$this->user->id}/favorites");
        $response->assertForbidden();
    }

    public function test_user_can_like_book(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->postJson("/api/v1/users/{$this->user->id}/favorites/{$this->newBook->id}");
        $response->assertOk();
    }

    public function test_user_cannot_like_book_for_other_user(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->postJson("/api/v1/users/{$this->user->id}/favorites/{$this->newBook->id}");
        $response->assertForbidden();
    }

    public function test_user_can_dislike_book(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/users/{$this->user->id}/favorites/{$this->favoriteBook->id}");
        $response->assertAccepted();
    }

    public function test_user_cannot_dislike_book_for_other_user(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson("/api/v1/users/{$this->user->id}/favorites/{$this->favoriteBook->id}");
        $response->assertForbidden();
    }

    public function test_dislike_returns_bad_request_when_not_in_favorites(): void
    {
        Sanctum::actingAs($this->user);

        $response = $this->deleteJson("/api/v1/users/{$this->user->id}/favorites/{$this->newBook->id}");
        $response->assertStatus(400);
    }
}
