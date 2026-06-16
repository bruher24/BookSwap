<?php

namespace Tests\Feature;

use App\Models\Genre;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class GenreApiTest extends TestCase
{
    use RefreshDatabase;

    private array $genreCreatePayload;

    private array $genreUpdatePayload;

    private array $genreWrongPayload = [
        'name' => 123,
    ];

    private User $admin;
    private User $user;
    private Genre $genre;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->createOne(['name' => 'admin']);
        $this->admin = User::factory()->createOne();
        $this->admin->roles()->attach($role);

        $this->user = User::factory()->createOne();
        $this->genre = Genre::factory()->createOne();

        $this->genreCreatePayload = Genre::factory()->raw();
        $this->genreUpdatePayload = Genre::factory()->raw();
    }

    public function test_index_genre(): void
    {
        $response = $this->getJson('/api/v1/genres');
        $response->assertOk();
    }

    public function test_get_genre(): void
    {
        $response = $this->getJson("/api/v1/genres/{$this->genre->id}");
        $response->assertOk();
    }

    public function test_not_found_get_genre(): void
    {
        $newId = (int)$this->genre->id + 1;
        $response = $this->getJson("/api/v1/genres/$newId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_genre(): void
    {
        $response = $this->postJson('/api/v1/genres', $this->genreCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_genre(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/genres', $this->genreCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/genres', $this->genreCreatePayload);
        $response->assertCreated();
    }

    public function test_validation_error_create_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->postJson('/api/v1/genres', $this->genreWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_genre(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->patchJson("/api/v1/genres/{$this->genre->id}", $this->genreUpdatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_update_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/genres/{$this->genre->id}", $this->genreUpdatePayload);
        $response->assertOk();
    }

    public function test_validation_error_update_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->patchJson("/api/v1/genres/{$this->genre->id}", $this->genreWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = (int)$this->genre->id + 1;
        $response = $this->patchJson("/api/v1/genres/$newId", $this->genreUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_genre(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/genres/{$this->genre->id}");
        $response->assertForbidden();
    }

    public function test_admin_can_delete_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->deleteJson("/api/v1/genres/{$this->genre->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_genre(): void
    {
        Sanctum::actingAs($this->admin);
        $newId = (int)$this->genre->id + 1;
        $response = $this->deleteJson("/api/v1/genres/$newId");
        $response->assertAccepted();
    }
}
