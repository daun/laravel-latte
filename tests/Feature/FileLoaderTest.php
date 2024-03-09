<?php

test('resolves files from root', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('loader.include')->assertSee('Welcome to Laravel Latte');
});

test('resolves files from subfolders', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('loader.nested.include')->assertSee('Welcome to Laravel Latte');
});
