<?php

namespace Tests\Feature;

use App\Models\Filter;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class FilterApiTest extends TestCase
{
    use RefreshDatabase;

    private array $filterCreatePayload = [
        'name' => 'Популярные',
        'by_fields' => 'name,publishing_house',
        'is_active' => true,
    ];

    private array $filterUpdatePayload = [
        'name' => 'Актуальные',
        'by_fields' => 'name,publication_year',
        'is_active' => false,
    ];

    private array $filterWrongPayload = [
        'name' => 1,
        'by_fields' => 2,
        'is_active' => 'bad',
    ];

    private User $admin;
    private User $user;
    private Filter $filter;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        $role = Role::factory()->createOne(['name' => 'admin']);
        $this->admin = User::factory()->createOne();
        $this->admin->roles()->attach($role);

        $this->user = User::factory()->createOne();
        $this->filter = Filter::factory()->createOne();
    }

    public function test_index_filter(): void
    {
        $response = $this->getJson('/api/v1/filters');
        $response->assertOk();
    }

    public function test_get_filter(): void
    {
        $response = $this->getJson("/api/v1/filters/{$this->filter->id}");
        $response->assertOk();
    }

    public function test_not_found_get_filter(): void
    {
        $newId = (int)$this->filter->id + 1;
        $response = $this->getJson("/api/v1/filters/$newId");
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_filter(): void
    {
        $response = $this->postJson('/api/v1/filters', $this->filterCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_create_filter(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->postJson('/api/v1/filters', $this->filterCreatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_create_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->postJson('/api/v1/filters', $this->filterCreatePayload);
        $response->assertCreated()
            ->assertJsonPath('data.filter.name', 'Популярные');

        $this->assertDatabaseHas('filters', [
            'name' => 'Популярные',
            'by_fields' => 'name,publishing_house',
        ]);
    }

    public function test_validation_error_create_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->postJson('/api/v1/filters', $this->filterWrongPayload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_update_filter(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->putJson("/api/v1/filters/{$this->filter->id}", $this->filterUpdatePayload);
        $response->assertForbidden();
    }

    public function test_admin_can_update_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->putJson("/api/v1/filters/{$this->filter->id}", $this->filterUpdatePayload);
        $response->assertOk()
            ->assertJsonPath('data.filter.name', 'Актуальные');
    }

    public function test_validation_error_update_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->putJson("/api/v1/filters/{$this->filter->id}", $this->filterWrongPayload);
        $response->assertStatus(422);
    }

    public function test_not_found_update_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $newId = (int)$this->filter->id + 1;
        $response = $this->putJson("/api/v1/filters/$newId", $this->filterUpdatePayload);
        $response->assertNotFound();
    }

    public function test_user_cannot_delete_filter(): void
    {
        Sanctum::actingAs($this->user);
        $response = $this->deleteJson("/api/v1/filters/{$this->filter->id}");
        $response->assertForbidden();
    }

    public function test_admin_can_delete_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $response = $this->deleteJson("/api/v1/filters/{$this->filter->id}");
        $response->assertAccepted();
    }

    public function test_not_found_delete_filter(): void
    {
        Sanctum::actingAs($this->admin, ['admin']);
        $newId = (int)$this->filter->id + 1;
        $response = $this->deleteJson("/api/v1/filters/$newId");
        $response->assertAccepted();
    }
}
