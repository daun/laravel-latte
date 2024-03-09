<?php

test('resolves layout', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('layout.view-with-layout')
        ->assertSee('Layout Parent')
        ->assertSee('View With Layout');
});

test('resolves without layout', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('layout.view-without-layout')
        ->assertDontSee('Layout Parent')
        ->assertSee('View Without Layout');
});

test('provides default layout', function () {
    $app = $this->createApplication();
    $this->modifyConfig($app, 'latte.default_layout', 'layout.default-layout');
    $this->createAndBootServiceProvider($app);

    $this->view('layout.view-without-layout')
        ->assertDontSee('Layout Parent')
        ->assertSee('Default Layout')
        ->assertSee('View Without Layout');
});

test('does not override set layout', function () {
    $app = $this->createApplication();
    $this->modifyConfig($app, 'latte.default_layout', 'layout.default-layout');
    $this->createAndBootServiceProvider($app);

    $this->view('layout.view-with-layout')
        ->assertSee('Layout Parent')
        ->assertDontSee('Default Layout')
        ->assertSee('View With Layout');
});
