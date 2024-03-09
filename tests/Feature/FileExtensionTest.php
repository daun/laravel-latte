<?php

test('accepts alternative file extension', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    // should load welcome-alt[.latte.html]
    $this->view('welcome-alt')->assertSee('Welcome to Alternative Laravel Latte');
});

test('prefers standard file extension', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    // should load extensions[.latte] instead of extensions[.latte.html]
    $this->view('extensions')->assertSee('[.latte]');
});

test('allows custom file extension', function () {
    $app = $this->createApplication();
    $this->modifyConfig($app, 'latte.file_extensions', ['latte.custom']);
    $this->createAndBootServiceProvider($app);

    // should load extensions[.latte.custom]
    $this->view('extensions')->assertSee('[.latte.custom]');
});
