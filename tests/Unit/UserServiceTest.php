<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    // TODO: перенести в feature, добавив вызов api
    public function test_create_user(): void
    {
        Event::fake();

        $data = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
            'remember_token' => '12345',
        ];

        $service = new UserService();
        $user = $service->create($data);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', ['email' => 'john@doe.com']);
    }
}
