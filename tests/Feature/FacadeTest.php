<?php

use Daun\LaravelLatte\Facades\Latte;

test('creates correct facade instance', function () {
    $app = $this->getApplication();
    $this->createAndBootServiceProvider($app);
    Latte::setFacadeApplication($app);

    expect(Latte::getFacadeRoot())->toBeInstanceOf('Latte\Engine');
});
