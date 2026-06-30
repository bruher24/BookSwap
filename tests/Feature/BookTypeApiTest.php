<?php

namespace Tests\Feature;

use App\Models\BookType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class BookTypeApiTest extends TestCase
{
    use RefreshDatabase;

    private array $bookTypeCreatePayload;

    private array $bookTypeUpdatePayload;

    private array $bookTypeWrongPayload = [
        'name' => 123,
    ];

    private User $admin;
    private User $user;
    private BookType $bookType;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->createOne(['name' => 'admin']);
        $this->admin = User::factory()->createOne();
        $this->admin->roles()->attach($role);

        $this->user = User::factory()->createOne();
        $this->bookType = BookType::factory()->createOne();

        $this->bookTypeCreatePayload = BookType::factory()->raw();
        $this->bookTypeUpdatePayload = BookType::factory()->raw();
    }

    public function test_index_book_type(): void
    {
        $response = $this->getJson('/api/v1/book_types');
        $response->assertOk();
    }

    public function test_get_book_type(): void
    {
        $response = $this->getJson("/api/v1/book_types/{$this->bookType->id}");
        $response->assertOk();
    }

    public function test_not_found_get_book_type(): void
    {
        $newId = $this->bookType->id + 1;
        $response = $this->getJson("/api/v1/book_types/$newId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_book_type(): void
    {
        $response = $this->postJson('/api/v1/book_types', $this->bookTypeCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_book_type(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/book_types', $this->bookTypeCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->postJson('/api/v1/book_types', $this->bookTypeCreatePayload);
        $response->assertCreated();
    }

    public function test_validation_error_create_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->postJson('/api/v1/book_types', $this->bookTypeWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_book_type(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/book_types/{$this->bookType->id}", $this->bookTypeUpdatePayload);
        $response->assertForbidden();
    }

    public function test_guest_cannot_update_book_type(): void
    {
        $response = $this->patchJson("/api/v1/book_types/{$this->bookType->id}", $this->bookTypeUpdatePayload);
        $response->assertUnauthorized();
    }

    public function test_admin_can_update_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->patchJson("/api/v1/book_types/{$this->bookType->id}", $this->bookTypeUpdatePayload);
        $response->assertOk();
    }

    public function test_validation_error_update_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->patchJson("/api/v1/book_types/{$this->bookType->id}", $this->bookTypeWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $newId = $this->bookType->id + 1;
        $response = $this->patchJson("/api/v1/book_types/$newId", $this->bookTypeUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_book_type(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/book_types/{$this->bookType->id}");
        $response->assertForbidden();
    }

    public function test_guest_cannot_delete_book_type(): void
    {
        $response = $this->deleteJson("/api/v1/book_types/{$this->bookType->id}");
        $response->assertUnauthorized();
    }

    public function test_admin_can_delete_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->deleteJson("/api/v1/book_types/{$this->bookType->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_book_type(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $newId = $this->bookType->id + 1;
        $response = $this->deleteJson("/api/v1/book_types/$newId");
        $response->assertAccepted();
    }
}
