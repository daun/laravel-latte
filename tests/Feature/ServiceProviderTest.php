<?php

use Daun\LaravelLatte\LatteFileLoader;
use Daun\LaravelLatte\ViewEngineBridge;
use Latte\Engine;

test('provides bindings', function () {
    // Make sure not bound before
    foreach ($this->provider->provides() as $binding) {
        expect($this->app->bound($binding))->toBeFalse();
    }

    // Register and boot provider
    $this->bootServiceProvider();

    // Now make sure bound after
    foreach ($this->provider->provides() as $binding) {
        expect($this->app->bound($binding))->toBeTrue();
    }
});

test('binds view engine bridge', function () {
    $this->bootServiceProvider();

    expect($this->app['latte.bridge'])->toBeInstanceOf(ViewEngineBridge::class);
});

test('binds latte engine', function () {
    $this->bootServiceProvider();

    expect($this->app['latte.engine'])->toBeInstanceOf(Engine::class);
});

test('binds latte loader', function () {
    $this->bootServiceProvider();

    expect($this->app['latte.loader'])->toBeInstanceOf(LatteFileLoader::class);
});

test('resolves view engine bridge', function () {
    $this->bootServiceProvider();

    $engine = $this->app['view']->getEngineResolver()->resolve('latte');
    expect($engine)->toBeInstanceOf(ViewEngineBridge::class);
});

test('resolves latte engine', function () {
    $this->bootServiceProvider();

    $called = false;
    $this->app->resolving('latte.engine', function () use (&$called) {
        $called = true;
    });

    $this->app['view']->getEngineResolver()->resolve('latte');

    expect($called)->toBeTrue();
});
