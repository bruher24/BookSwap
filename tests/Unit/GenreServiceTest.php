<?php

namespace Tests\Unit;

use App\Services\GenreService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index()
    {
        $this->refreshDatabase();
        $genreService = new GenreService();
        $index = $genreService->getAll();
        dump($index);
        self::assertEquals(0, $index->count());
    }
}
