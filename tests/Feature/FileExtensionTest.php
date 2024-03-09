<?php

test('accepts alternative file extension', function () {
    $this->bootServiceProvider();

    // should load welcome-alt[.latte.html]
    $this->view('welcome-alt')->assertSee('Welcome to Alternative Laravel Latte');
});

test('prefers standard file extension', function () {
    $this->bootServiceProvider();

    // should load view[.latte] instead of view[.latte.html]
    $this->view('extensions.view')->assertSee('[.latte]');
});

test('allows custom file extension', function () {
    $this->modifyConfig('latte.file_extensions', ['latte.custom']);
    $this->bootServiceProvider();

    // should load view[.latte.custom]
    $this->view('extensions.view')->assertSee('[.latte.custom]');
});
