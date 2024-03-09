<?php

use Daun\LaravelLatte\Events\LatteEngineCreated;
use Illuminate\Support\Facades\Event;

test('dispatches event on engine creation', function () {
    Event::fake();
    Event::assertNotDispatched(LatteEngineCreated::class);

    $this->bootServiceProvider();
    Event::assertNotDispatched(LatteEngineCreated::class);

    expect($this->app['latte.engine'])->toBeTruthy();
    Event::assertDispatched(LatteEngineCreated::class);
});
