<?php

test('resolves files from root', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('loader')->assertSee('Welcome to Laravel Latte');
});

test('resolves files from subfolders', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('nested.loader')->assertSee('Welcome to Laravel Latte');
});
