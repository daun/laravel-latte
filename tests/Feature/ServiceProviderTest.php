<?php

test('registers bindings', function () {
    $app = $this->getApplication();
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
