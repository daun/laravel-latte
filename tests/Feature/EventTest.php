<?php

use Daun\LaravelLatte\Events\LatteEngineCreated;
use Illuminate\Support\Facades\Event;

test('dispatches event on engine creation', function () {
    Event::fake();
    Event::assertNotDispatched(LatteEngineCreated::class);

    $this->bootServiceProvider();
    Event::assertNotDispatched(LatteEngineCreated::class);

    $this->app->get('latte.engine');

    Event::assertDispatched(LatteEngineCreated::class);
});

test('passes engine with creation event', function () {
    Event::fake();

    $this->bootServiceProvider();
    $engine = $this->app->get('latte.engine');

    Event::assertDispatched(LatteEngineCreated::class, function (LatteEngineCreated $event) use ($engine) {
        $this->assertEquals($engine, $event->engine);

        return true;
    });
});
