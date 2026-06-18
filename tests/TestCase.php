<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Override;

abstract class TestCase extends BaseTestCase
{
    #[Override]
    public function setUp(): void
    {
        parent::setUp();

        User::flushEventListeners();
    }
}
