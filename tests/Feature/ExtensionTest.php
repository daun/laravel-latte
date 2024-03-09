<?php

use TestExtensions\CustomExtension;

test('registers custom extensions', function () {
    $this->modifyConfig('latte.extensions', [CustomExtension::class]);
    $this->bootServiceProvider();

    /** @var \Latte\Engine $engine */
    $engine = $this->app->get('latte.engine');
    $extensions = collect($engine->getExtensions())->map(fn ($extension) => get_class($extension));
    expect($extensions)->toContain(CustomExtension::class);
});

test('provides filters from custom extensions', function () {
    $this->modifyConfig('latte.extensions', [CustomExtension::class]);
    $this->bootServiceProvider();

    $this->view('extensions.custom', ['value' => 'something'])
        ->assertSee('This will say CUSTOM(something)');
});
