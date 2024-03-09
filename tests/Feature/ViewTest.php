<?php

test('renders views', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('welcome')->assertSee('Welcome to Laravel Latte');
});

test('passes data into view', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->view('basic-data', [
        'name' => 'John',
        'age' => 31,
        'hobbies' => ['fishing', 'hiking', 'camping'],
    ])->assertSee('My name is John')
        ->assertSee('I am 31 years old')
        ->assertSee('I enjoy fishing, hiking, camping');
});
