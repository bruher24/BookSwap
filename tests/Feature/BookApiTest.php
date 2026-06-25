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

    private User $owner;
    private User $other;
    private Author $author;
    private Book $book;

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

        $this->bookCreatePayload = Book::factory()->raw();
        $this->bookUpdatePayload = Book::factory()->raw();

        $this->bookCreatePayload['author_id'] = $this->author->id;
        $this->bookCreatePayload['book_type_id'] = $bookType->id;
        $this->bookUpdatePayload['author_id'] = $this->author->id;
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
        $response->assertJsonPath('data.books.0.id', $this->book->id);
    }

    public function test_where_book_filters_by_name(): void
    {
        $this->book->update(['name' => 'Needle Book']);

        $response = $this->getJson('/api/v1/books/where?name=Needle');

        $response->assertOk();
        $response->assertJsonPath('data.books.0.id', $this->book->id);
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
        $response->assertJsonPath('data.books.0.id', $this->book->id);
    }

    public function test_get_book(): void
    {
        $response = $this->getJson("/api/v1/books/{$this->book->id}");
        $response->assertOk();
    }

    public function test_not_found_get_book(): void
    {
        $newId = $this->book->id + 1;
        $response = $this->getJson("/api/v1/books/$newId");
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
        $response->assertJsonPath('data.book.user_id', $this->owner->id);
    }

    public function test_user_id_from_create_book_payload_is_ignored(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookCreatePayload['user_id'] = $this->other->id;
        $this->bookCreatePayload['author_id'] = $this->author->id;

        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);

        $response->assertCreated();
        $response->assertJsonPath('data.book.user_id', $this->owner->id);
    }

    public function test_user_can_create_book_with_new_author_without_optional_author_fields(): void
    {
        Sanctum::actingAs($this->owner);
        unset($this->bookCreatePayload['author_id'], $this->bookCreatePayload['author_id1'], $this->bookCreatePayload['author_id2']);
        $this->bookCreatePayload['authorLastname'] = 'Newlastname';
        $this->bookCreatePayload['authorFirstname'] = 'Newfirstname';
        unset($this->bookCreatePayload['authorPatronymic'], $this->bookCreatePayload['authorBirthdate']);

        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);

        $response->assertCreated();
        $this->assertDatabaseHas('authors', [
            'user_id' => $this->owner->id,
            'lastname' => 'Newlastname',
            'firstname' => 'Newfirstname',
            'patronymic' => null,
            'birthdate' => null,
        ]);
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
        $payload['cover'] = UploadedFile::fake()->image('updated-cover.png');

        $response = $this->patch("/api/v1/books/{$this->book->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseHas('covers', [
            'id' => $response->json('data.book.cover_id'),
            'user_id' => $this->owner->id,
        ]);
        Storage::disk('public')->assertExists(Cover::find($response->json('data.book.cover_id'))->src);
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
        $newId = $this->book->id + 1;

        $response = $this->patchJson("/api/v1/books/$newId", $this->bookUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_others_book(): void
    {
        Sanctum::actingAs($this->other);
        $response = $this->deleteJson("/api/v1/books/{$this->book->id}");
        $response->assertForbidden();
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
        $newId = $this->book->id + 1;
        $response = $this->deleteJson("/api/v1/books/$newId");
        $response->assertAccepted();
    }
}
