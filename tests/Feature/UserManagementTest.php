<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Depends;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, withFaker;

    // TODO: тесты api лучше в postman
    //  а здесь тесты компонентов приложения (сервисы, вьюхи, передаваемые переменные)
    public function test_create(): void
    {
        $response = $this->post('users/create', [
            'name' => 'test name',
            'email' => 'test@email.com',
            'password' => '1234',
            'photo_id' => 1,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Вы успешо зарегистрировались!');
    }
}
