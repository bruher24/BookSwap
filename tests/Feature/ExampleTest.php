<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Events\MessageReceived;
use App\Models\Message;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_orders_can_be_shipped(): void
    {
        Event::fake();

        MessageReceived::dispatch(new Message());

        // Assert that an event was dispatched...
        Event::assertDispatched(MessageReceived::class);

        // Assert an event was not dispatched...
//        Event::assertNotDispatched(MessageReceived::class);
        
    }
}
