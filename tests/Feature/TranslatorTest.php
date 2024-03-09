<?php

test('provides translations', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->latte("{_'messages.lorem'} = {_'messages.dolor.sit'}")->assertSee('ipsum = amet');
});

test('replaces params in translations', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->latte("{_'messages.welcome'}")->assertSee('Welcome, :name');
    $this->latte("{_'messages.welcome', [name: 'Adam']}")->assertSee('Welcome, Adam');
});

test('allows named translation arguments', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->latte("{_'messages.welcome', name: 'Eve'}")->assertSee('Welcome, Eve');
});

test('switches between locales', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);

    $this->latte("{_'messages.welcome', [name: 'Mary']}")->assertSee('Welcome, Mary');
    $this->latte("{_'messages.welcome', [name: 'Marie'], 'de'}")->assertSee('Willkommen, Marie');
});
