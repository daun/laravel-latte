<?php

use Daun\LaravelLatte\LatteFileLoader;
use Daun\LaravelLatte\ViewEngineBridge;
use Latte\Engine;

test('provides bindings', function () {
    $app = $this->createApplication();
    $provider = $this->createServiceProvider($app);

    // Make sure not bound before
    foreach ($provider->provides() as $binding) {
        expect($app->bound($binding))->toBeFalse();
    }

    // Register and boot provider
    $provider->register();
    $provider->boot();

    // Now make sure bound after
    foreach ($provider->provides() as $binding) {
        expect($app->bound($binding))->toBeTrue();
    }
});

test('binds view engine bridge', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    expect($app['latte.bridge'])->toBeInstanceOf(ViewEngineBridge::class);
});

test('binds latte engine', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    expect($app['latte.engine'])->toBeInstanceOf(Engine::class);
});

test('binds latte loader', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    expect($app['latte.loader'])->toBeInstanceOf(LatteFileLoader::class);
});

test('resolves view engine bridge', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    ray($app['view']->getEngineResolver());

    $engine = $app['view']->getEngineResolver()->resolve('latte');
    expect($engine)->toBeInstanceOf(ViewEngineBridge::class);
});

test('resolves latte engine', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $called = false;
    $app->resolving('latte.engine', function () use (&$called) {
        $called = true;
    });

    $app['view']->getEngineResolver()->resolve('latte');

    expect($called)->toBeTrue();
});
