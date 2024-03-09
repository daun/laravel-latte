<?php

use Daun\LaravelLatte\Facades\Latte;
use Latte\Engine;

test('creates correct facade instance', function () {
    $app = $this->createApplication();
    $this->createAndBootServiceProvider($app);
    Latte::setFacadeApplication($app);

    expect(Latte::getFacadeRoot())->toBeInstanceOf(Engine::class);
});
