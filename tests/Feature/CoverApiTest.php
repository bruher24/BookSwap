<?php

namespace Tests\Feature;

use App\Models\Cover;
use App\Models\Role;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class CoverApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;
    private User $other;
    private Cover $cover;
    private UploadedFile $coverFile;
    private array $coverCreatePayload;

    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->unverified()->createOne();
        $role = Role::factory()->createOne(['name' => 'admin']);
        $this->admin->roles()->attach($role);

        $this->owner = User::factory()->unverified()->createOne();
        $this->other = User::factory()->unverified()->createOne();

        $this->cover = Cover::factory()->createOne(['user_id' => $this->owner->id]);

        $this->coverFile = UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg');

        $this->coverCreatePayload = Cover::factory()->raw();
    }

    public function test_user_cannot_index_cover(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/covers');
        $response->assertForbidden();
    }

    public function test_guest_cannot_index_cover(): void
    {
        $response = $this->getJson('/api/v1/covers');
        $response->assertUnauthorized();
    }

    public function test_admin_can_index_cover(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/covers');
        $response->assertOk();
    }

    public function test_guest_cannot_get_cover(): void
    {
        $response = $this->getJson('/api/v1/covers/' . $this->cover->id);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_get_cover(): void
    {
        Sanctum::actingAs($this->owner);
        $response = $this->getJson('/api/v1/covers/' . $this->cover->id);
        $response->assertForbidden();
    }

    public function test_admin_can_get_cover(): void
    {
        Sanctum::actingAs($this->admin);
        $response = $this->getJson('/api/v1/covers/' . $this->cover->id);
        $response->assertOk();
    }

    public function test_not_found_get_cover(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/covers/999');
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_cover(): void
    {
        $response = $this->postJson('/api/v1/covers', $this->coverCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_cover(): void
    {
        Sanctum::actingAs($this->owner);

        $payload = [
            'file' => $this->coverFile,
            'user_id' => $this->owner->id,
        ];

        $response = $this->post('/api/v1/covers', $payload);
        $response->assertCreated();

        $this->assertDatabaseHas('covers', [
            'user_id' => $this->owner->id,
        ]);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($response->json('data.attributes.src'));
    }

    public function test_validation_error_create_cover(): void
    {
        Sanctum::actingAs($this->owner);

        $payload = [
            'file' => 2,
            'user_id' => '-1235',
        ];

        $response = $this->postJson('/api/v1/covers', $payload);
        $response->assertStatus(422);
    }

    public function test_guest_cannot_delete_cover(): void
    {
        $response = $this->deleteJson('/api/v1/covers/' . $this->cover->id);
        $response->assertUnauthorized();
    }

    public function test_user_cannot_delete_cover(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson('/api/v1/covers/' . $this->cover->id);
        $response->assertForbidden();
    }

    public function test_admin_can_delete_cover(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson('/api/v1/covers/' . $this->cover->id);
        $response->assertAccepted();

        $this->assertSoftDeleted('covers', [
            'id' => $this->cover->id,
        ]);
    }

    public function test_not_found_delete_cover(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->deleteJson('/api/v1/covers/999');
        $response->assertAccepted();
    }
}
