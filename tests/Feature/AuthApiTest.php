<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register(): void
    {
        $payload = [
            'name' => 'Test User',
            'email' => '',
        ];
            }
}
