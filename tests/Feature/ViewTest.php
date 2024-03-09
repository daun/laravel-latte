<?php

test('renders views', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('welcome')->assertSee('Welcome to Laravel Latte');
});
