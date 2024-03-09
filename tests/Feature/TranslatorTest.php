<?php

test('provides translation macro', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.lorem'} = {_'messages.dolor.sit'}")->assertSee('ipsum = amet');
});

test('provides translation filter', function () {
    $this->bootServiceProvider();

    $this->latte("{('messages.lorem'|translate)} = {('messages.dolor.sit'|translate)}")->assertSee('ipsum = amet');
});

test('provides translation filter shorthand', function () {
    $this->bootServiceProvider();

    $this->latte("{('messages.lorem'|trans)} = {('messages.dolor.sit'|trans)}")->assertSee('ipsum = amet');
});

test('replaces params in translations', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome'}")->assertSee('Welcome, :name');
    $this->latte("{_'messages.welcome', [name: 'Adam']}")->assertSee('Welcome, Adam');
    $this->latte("{('messages.welcome'|translate:[name: 'Adam'])}")->assertSee('Welcome, Adam');
});

test('allows named translation arguments', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome', name: 'Eve'}")->assertSee('Welcome, Eve');
    $this->latte("{_'messages.welcome', name: 'Eve', age: 16}")->assertSee('Welcome, Eve');
});

test('allows overriding locale', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.welcome', [name: 'Mary']}")->assertSee('Welcome, Mary');
    $this->latte("{_'messages.welcome', [name: 'Marie'], 'de'}")->assertSee('Willkommen, Marie');
});

test('allows overriding locale using shorthand', function () {
    $this->bootServiceProvider();

    $this->latte("{_'messages.hello'}")->assertSee('Hello');
    $this->latte("{_'messages.hello', 'de'}")->assertSee('Hallo');
});

test('pluralizes translations', function () {
    $this->bootServiceProvider();

    $this->latte("{('messages.apples'|transChoice:0, [what: 'apples'])}")->assertSee('There is nothing');
    $this->latte("{('messages.apples'|transChoice:3, [what: 'apples'])}")->assertSee('There are 3 apples');
    $this->latte("{('messages.apples'|transChoice:50, [what: 'apples'])}")->assertSee('There are many apples');
});

test('pluralizes translations overriding locale', function () {
    $this->bootServiceProvider();

    $this->latte("{('messages.apples'|transChoice:0, [what: 'Äpfel'], 'de')}")->assertSee('Es gibt nichts');
    $this->latte("{('messages.apples'|transChoice:0, 'de')}")->assertSee('Es gibt nichts');
    $this->latte("{('messages.apples'|transChoice:3, [what: 'Äpfel'], 'de')}")->assertSee('Es gibt 3 Äpfel');
    $this->latte("{('messages.apples'|transChoice:50, [what: 'Äpfel'], 'de')}")->assertSee('Es gibt viele Äpfel');
});

test('throws for invalid translator', function () {
    $this->modifyConfig('latte.translator', 5);
    $this->bootServiceProvider();

    expect(fn () => $this->app->get('latte.engine'))
        ->toThrow(\Exception::class, 'Invalid translator extension: must be class name or null.');
});
