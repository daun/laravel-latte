<?php

test('provides translations', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.lorem'} = {_'messages.dolor.sit'}")->assertSee('ipsum = amet');
});

test('replaces params in translations', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome'}")->assertSee('Welcome, :name');
    $this->latte("{_'messages.welcome', [name: 'Adam']}")->assertSee('Welcome, Adam');
});

test('allows named translation arguments', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome', name: 'Eve'}")->assertSee('Welcome, Eve');
});

test('switches between locales', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome', [name: 'Mary']}")->assertSee('Welcome, Mary');
    $this->latte("{_'messages.welcome', [name: 'Marie'], 'de'}")->assertSee('Willkommen, Marie');
});
