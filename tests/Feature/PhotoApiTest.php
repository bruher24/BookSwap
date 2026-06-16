<?php

namespace Tests\Feature;

use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class PhotoApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $owner;
    private User $other;
    private Photo $photo;
    private UploadedFile $photoFile;
    private array $photoCreatePayload;

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

        $this->photo = Photo::factory()->createOne(['user_id' => $this->owner->id]);

        $this->photoFile = UploadedFile::fake()->image('photo.jpg');

        $this->photoCreatePayload = Photo::factory()->raw();
    }

    public function test_user_cannot_index_photo(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->getJson('/api/v1/photos');
        $response->assertForbidden();
    }

    public function test_admin_can_index_photo(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/photos');
        $response->assertOk();
    }

    public function test_everybody_can_get_photo(): void
    {
        $response = $this->getJson('/api/v1/photos/' . $this->photo->id);
        $response->assertOk();

        Sanctum::actingAs($this->owner);
        $response = $this->getJson('/api/v1/photos/' . $this->photo->id);
        $response->assertOk();

        Sanctum::actingAs($this->admin);
        $response = $this->getJson('/api/v1/photos/' . $this->photo->id);
        $response->assertOk();
    }

    public function test_not_found_get_photo(): void
    {
        $response = $this->getJson('/api/v1/photos/999');
        $response->assertNotFound();
    }

    public function test_guest_cannot_create_photo(): void
    {
        $response = $this->postJson('/api/v1/photos', $this->photoCreatePayload);
        $response->assertUnauthorized();
    }

    public function test_user_can_create_photo(): void
    {
        Sanctum::actingAs($this->owner);

        $payload = [
            'src' => $this->photoFile,
            'user_id' => $this->owner->id,
        ];

        $response = $this->post('/api/v1/photos', $payload);
        $response->assertCreated();

        $this->assertDatabaseHas('photos', [
            'user_id' => $this->owner->id,
        ]);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $disk->assertExists($response->json('data.photo.src'));
    }

    public function test_validation_error_create_photo(): void
    {
        Sanctum::actingAs($this->owner);

        $payload = [
            'src' => 2,
            'user_id' => '-1235',
        ];

        $response = $this->postJson('/api/v1/photos', $payload);
        $response->assertStatus(422);
    }

    public function test_user_cannot_delete_others_photo(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson('/api/v1/photos/' . $this->photo->id);
        $response->assertForbidden();
    }

    public function test_user_can_delete_owned_photo(): void
    {
        Sanctum::actingAs($this->owner);

        $response = $this->deleteJson('/api/v1/photos/' . $this->photo->id);
        $response->assertAccepted();

        $this->assertSoftDeleted('photos', [
            'id' => $this->photo->id,
        ]);
    }

    public function test_not_found_delete_photo(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson('/api/v1/photos/999');
        $response->assertAccepted();
    }
}
