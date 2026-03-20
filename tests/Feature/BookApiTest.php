<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class BookApiTest extends TestCase
{
    use RefreshDatabase;

    private array $bookCreatePayload = [
        'name' => 'Преступление и наказание',
        'page_count' => 300,
        'publishing_house' => 'Эксмо',
        'publication_year' => '2012',
        'isbn' => '1111111111111',
    ];

    private array $bookUpdatePayload = [
        'name' => 'Идиот',
        'page_count' => 420,
        'publishing_house' => 'Азбука',
        'publication_year' => '2015',
        'isbn' => '2222222222222',
    ];

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
    private BookType $bookType;
    private Cover $cover;
    private Book $book;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->createOne();
        $this->other = User::factory()->createOne();

        $this->author = Author::factory()->createOne(['user_id' => $this->owner->id]);
        $this->bookType = BookType::factory()->createOne();
        $this->cover = Cover::factory()->createOne(['user_id' => $this->owner->id]);
        $this->book = Book::factory()->createOne([
            'user_id' => $this->owner->id,
            'book_type_id' => $this->bookType->id,
            'cover_id' => $this->cover->id,
        ]);
        $this->book->authors()->attach($this->author);

        $this->bookCreatePayload['author_id'] = $this->author->id;
        $this->bookCreatePayload['book_type_id'] = $this->bookType->id;
        $this->bookUpdatePayload['author_id'] = $this->author->id;
        $this->bookUpdatePayload['book_type_id'] = $this->bookType->id;
    }

    public function test_index_book(): void
    {
        $response = $this->getJson('/api/v1/books');
        $response->assertOk();
    }

    public function test_get_book(): void
    {
        $response = $this->getJson("/api/v1/books/{$this->book->id}");
        $response->assertOk();
    }

    public function test_not_found_get_book(): void
    {
        $newId = (int)$this->book->id + 1;
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
        $this->bookCreatePayload['user_id'] = $this->owner->id;

        $response = $this->postJson('/api/v1/books', $this->bookCreatePayload);
        $response->assertCreated()
            ->assertJsonPath('data.book.name', 'Преступление и наказание');

        $this->assertDatabaseHas('books', [
            'name' => 'Преступление и наказание',
            'user_id' => $this->owner->id,
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
        $this->bookUpdatePayload['user_id'] = $this->other->id;

        $response = $this->putJson("/api/v1/books/{$this->book->id}", $this->bookUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_update_owned_book(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookUpdatePayload['user_id'] = $this->owner->id;

        $response = $this->putJson("/api/v1/books/{$this->book->id}", $this->bookUpdatePayload);
        $response->assertOk()
            ->assertJsonPath('data.book.name', 'Идиот');
    }

    public function test_validation_error_update_book(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->putJson("/api/v1/books/{$this->book->id}", $this->bookWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_book(): void
    {
        Sanctum::actingAs($this->owner);
        $this->bookUpdatePayload['user_id'] = $this->owner->id;
        $newId = (int)$this->book->id + 1;

        $response = $this->putJson("/api/v1/books/$newId", $this->bookUpdatePayload);
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
        $newId = (int)$this->book->id + 1;
        $response = $this->deleteJson("/api/v1/books/$newId");
        $response->assertAccepted();
    }
}
