<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private array $bookCreatePayload;
    private array $bookUpdatePayload;
    private array $bookWrongPayload = [
        'user_id' => 'nope',
        'name' => 123,
        'author_id' => 'bad',
        'page_count' => 0,
        'book_type_id' => 'bad',
        'isbn' => 'short',
    ];
    private int $notFoundId = 9999;
    private User $owner;
    private User $other;
    private Author $author;
    private Book $book;
    private Book $otherBook;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->owner = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        $this->author = Author::factory()->createOne(['user_id' => $this->owner->id]);
        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne(['user_id' => $this->owner->id]);
        $this->book = Book::factory()->createOne([
            'user_id' => $this->owner->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);
        $this->book->authors()->attach($this->author);

        $otherBookType = BookType::factory()->createOne();
        $otherCover = Cover::factory()->createOne(['user_id' => $this->other->id]);
        $this->otherBook = Book::factory()->createOne([
            'user_id' => $this->other->id,
            'book_type_id' => $otherBookType->id,
            'cover_id' => $otherCover->id,
        ]);
        $this->otherBook->authors()->attach($this->author);

        $this->bookCreatePayload = Book::factory()->raw();
        $this->bookUpdatePayload = Book::factory()->raw();

        $this->bookCreatePayload['authors_ids'] = [$this->author->id];
        $this->bookCreatePayload['book_type_id'] = $bookType->id;
        $this->bookUpdatePayload['authors_ids'] = [$this->author->id];
        $this->bookUpdatePayload['book_type_id'] = $bookType->id;
    }

    public function test_index_book(): void
    {
        $response = $this->getJson('/api/v1/books');
        $response->assertOk();
    }

    public function test_where_book_filters_available_books(): void
    {
        $response = $this->getJson('/api/v1/books/where?book_type_id[]=' . (string)$this->book->book_type_id);
        $response->assertOk();
        $response->assertJsonPath('data.0.id', (string)$this->book->id);
    }

    public function test_where_book_filters_by_name(): void
    {
        $this->book->update(['name' => 'Needle Book']);

        $response = $this->getJson('/api/v1/books/where?name=Needle');

        $response->assertOk();
        $response->assertJsonPath('data.0.id', (string)$this->book->id);
    }

    public function test_where_book_rejects_name_as_array(): void
    {
        $response = $this->getJson('/api/v1/books/where?name[]=Needle');

        $response->assertUnprocessable();
    }

    public function test_where_book_rejects_page_count_with_one_bound(): void
    {
        $response = $this->getJson('/api/v1/books/where?page_count[]=100');

        $response->assertUnprocessable();
    }

    public function test_where_book_filters_by_page_count_range(): void
    {
        $this->book->update(['page_count' => 200]);

        $response = $this->getJson('/api/v1/books/where?page_count[]=100&page_count[]=300');

        $response->assertOk();
        $response->assertJsonPath('data.0.id', (string)$this->book->id);
    }

    public function test_get_book(): void
    {
        $response = $this->getJson("/api/v1/books/{$this->book->id}");
        $response->assertOk();
    }

    public function test_not_found_get_book(): void
    {
        $response = $this->getJson("/api/v1/books/$this->notFoundId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_book(): void
    {
        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_book(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookCreatePayload['author_id'] = $this->author->id;

        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);
        $response->assertCreated();
        $response->assertJsonPath('data.attributes.user_id', $this->owner->id);
    }

    public function test_user_id_from_create_book_payload_is_ignored(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookCreatePayload['user_id'] = $this->other->id;
        $this->bookCreatePayload['authors_ids'] = [$this->author->id];

        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);

        $response->assertCreated();
        $response->assertJsonPath('data.attributes.user_id', $this->owner->id);
    }

    public function test_validation_error_create_book(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->postJson('/api/v1/books', $this->bookWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_others_book(): void
    {
        Sanctum::actingAs($this->other);
        $this->bookUpdatePayload['author_id'] = $this->author->id;

        $response = $this->patchJson("/api/v1/books/{$this->book->id}", $this->bookUpdatePayload);
        $response->assertForbidden();
    }

    public function test_guest_cannot_update_book(): void
    {
        $response = $this->patchJson("/api/v1/books/{$this->book->id}", $this->bookUpdatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_update_owned_book(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookUpdatePayload['author_id'] = $this->author->id;

        $response = $this->patchJson("/api/v1/books/{$this->book->id}", $this->bookUpdatePayload);
        $response->assertOk();
    }

    /**
     * @psalm-suppress PossiblyNullPropertyFetch, UndefinedInterfaceMethod
     */
    public function test_user_can_update_owned_book_cover(): void
    {
        Sanctum::actingAs($this->owner);
        $payload = $this->bookUpdatePayload;
        $payload['author_id'] = $this->author->id;
        $payload['condition'] = 'good';
        $payload['cover'] = UploadedFile::fake()->create('updated-cover.png', 100, 'image/png');

        $response = $this->patch("/api/v1/books/{$this->book->id}", $payload);

        $response->assertOk();
        $coverId = $response->json('data.attributes.cover_id');
        $this->assertDatabaseHas('covers', [
            'id' => $coverId,
            'user_id' => $this->owner->id,
        ]);
        Storage::disk('public')->assertExists(Cover::find($coverId)->src);
    }

    public function test_validation_error_update_book(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->patchJson("/api/v1/books/{$this->book->id}", $this->bookWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_book(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->patchJson("/api/v1/books/$this->notFoundId", $this->bookUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_others_book(): void
    {
        Sanctum::actingAs($this->other);
        $response = $this->deleteJson("/api/v1/books/{$this->book->id}");
        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_book(): void
    {
        $response = $this->deleteJson("/api/v1/books/{$this->book->id}");
        $response->assertUnauthorized();
    }

    public function test_user_can_delete_owned_book(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->deleteJson("/api/v1/books/{$this->book->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_book(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->deleteJson("/api/v1/books/$this->notFoundId");
        $response->assertAccepted();
    }

    public function test_user_can_index_owned_books(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/me/books');
        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', (string)$this->book->id);
    }

    public function test_guest_cannot_index_user_books(): void
    {
        $response = $this->getJson('/api/v1/me/books');
        $response->assertUnauthorized();
    }

    public function test_user_does_not_index_others_books(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/me/books');
        $response->assertOk();
        $response->assertJsonMissing(['id' => (string)$this->otherBook->id]);
    }
}
