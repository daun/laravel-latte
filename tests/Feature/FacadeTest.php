<?php

use Daun\LaravelLatte\Facades\Latte;
use Illuminate\Support\Str;
use Latte\Engine;

test('creates correct facade instance', function () {
    $this->bootServiceProvider();

    expect(Latte::getFacadeRoot())->toBeInstanceOf(Engine::class);
});

test('provides filters from facade', function () {
    $this->bootServiceProvider();

    Latte::addFilter('plural', fn ($str) => Str::plural($str));

    $this->latte('{$word|plural}', ['word' => 'car'])->assertSee('cars');
});
