<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Event;

class TestDocumentEvents extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClientCreationFiresDocumentWasCreatedEvent()
    {
        // Event::fake();

        assert(true);

        // Event::assertDispatched(OrderShipped::class, function ($e) use ($order) {
        //     return $e->order->id === $order->id;
        // });
    }
}
