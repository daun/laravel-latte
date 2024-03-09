<?php

use Daun\LaravelLatte\Facades\Latte;
use Latte\Engine;

test('creates correct facade instance', function () {
    $this->bootServiceProvider();

    expect(Latte::getFacadeRoot())->toBeInstanceOf(Engine::class);
});
