<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

final class AuthorApiTest extends TestCase
{
    use RefreshDatabase;

    private array $authorCreatePayload = [
        'lastname' => 'Толстой',
        'firstname' => 'Лев',
        'patronymic' => 'Николаевич',
        'birthdate' => '02.02.2002'
    ];

    private array $authorUpdatePayload = [
        'lastname' => 'Пушкин',
        'firstname' => 'Александр',
        'patronymic' => 'Сергеевич',
        'birthdate' => '05.05.2005'
    ];

    private User $owner;
    private Author $author;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create();
        $this->author = Author::factory()->create(['user_id' => $this->owner->id]);
    }

    public function test_guest_cannot_create_author(): void
    {
        $response = $this->postJson('/api/v1/authors', $this->authorCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_author(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->authorCreatePayload['user_id'] = $user->id;
        $response = $this->postJson('/api/v1/authors', $this->authorCreatePayload);
        $response->assertCreated()
            ->assertJsonPath('data.author.lastname', 'Толстой');
        $this->assertDatabaseHas('authors', $this->authorCreatePayload);
    }

    public function test_user_cannot_update_author(): void
    {
        $other = User::factory()->create();
        Sanctum::actingAs($other);

        $this->authorUpdatePayload['user_id'] = $other->id;
        $response = $this->putJson("/api/v1/authors/{$this->author->id}", $this->authorUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_update_author(): void
    {
        Sanctum::actingAs($this->owner);

        $this->authorUpdatePayload['user_id'] = $this->owner->id;
        $response = $this->putJson("/api/v1/authors/{$this->author->id}", $this->authorUpdatePayload);
        $response->assertOk()
            ->assertJsonPath('data.author.lastname', 'Пушкин');
    }

}
