<?php

use Daun\LaravelLatte\Facades\Latte;

test('facade instance', function () {
    $app = $this->getApplication();
    $this->bootServiceProvider($app);
    Latte::setFacadeApplication($app);

    expect(Latte::getFacadeRoot())->toBeInstanceOf('Latte\Engine');
});
