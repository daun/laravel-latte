<?php

namespace Daun\LaravelLatte\Tests\Facade;

use Daun\LaravelLatte\Tests\Base;
use Daun\LaravelLatte\ServiceProvider;
use Daun\LaravelLatte\Facades\Latte;

class LatteFacadeTest extends Base
{
    public function testFacadeInstance()
    {
        $this->bootApplication();

        $this->assertInstanceOf('Latte\Engine', Latte::getFacadeRoot());
    }

    protected function bootApplication()
    {
        $app = $this->getApplication();

        $provider = new ServiceProvider($app);
        $provider->register();
        $provider->boot();

        Latte::setFacadeApplication($app);

        return $app;
    }
}
