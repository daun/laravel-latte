<?php

test('resolves files from root', function () {
    $this->bootServiceProvider();

    $this->view('loader.include')->assertSee('Welcome to Laravel Latte');
});

test('resolves files from subfolders', function () {
    $this->bootServiceProvider();

    $this->view('loader.nested.include')->assertSee('Welcome to Laravel Latte');
});

test('resolves relative paths', function () {
    $this->bootServiceProvider();

    $this->view('loader.relative')->assertSee('Welcome to Laravel Latte');
});
