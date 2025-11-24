<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Services\AuthorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AuthorServiceTest extends TestCase
{
    use RefreshDatabase;

    // TODO: перенести в feature, добавив вызов api
    public function test_create(): void
    {
        $service = new AuthorService();
        $data = [
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'patronymic' => 'patronymic',
        ];
        $author = $service->create($data);
        $this->assertInstanceOf(Author::class, $author);
        $fullname = $author->fullName;
        $this->assertEquals('Lastname Firstname Patronymic', $fullname);
        $formattedName = $author->formattedName;
        $this->assertEquals('Lastname F. P.', $formattedName);
    }
}
