<?php

namespace Tests;

use Daun\LaravelLatte\ServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Mockery;
use Orchestra\Testbench\Concerns\CreatesApplication;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\Concerns\InteractsWithViews;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithViews;

    protected $root;

    protected function setUp(): void
    {
        $this->root = realpath(__DIR__ . '/../src');
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    protected function defineEnvironment(Application $app)
    {
        tap($app['config'], function (Repository $config) {
            $defaults = include __DIR__ . '/../config/latte.php';
            $config->set('latte', $defaults);
        });
    }

    protected function createServiceProvider(Application $app): ServiceProvider
    {
        return new ServiceProvider($app);
    }

    protected function createAndBootServiceProvider(Application $app): ServiceProvider
    {
        $provider = new ServiceProvider($app);
        $provider->register();
        $provider->boot();
        return $provider;
    }

    protected function addViewPaths(Application $app): void
    {
        $app['config']->set('view.paths', [__DIR__]);
    }
}
