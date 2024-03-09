<?php

namespace Tests;

use Daun\LaravelLatte\ServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Mockery;
use Orchestra\Testbench\Concerns\CreatesApplication;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $root;

    protected function setUp(): void
    {
        $this->root = realpath(__DIR__ . '/../src');
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @param array $customConfig that will override the default config.
     * @return Application
     */
    protected function getApplication(array $config = []): Application
    {
        $app = new Application(__DIR__);

        $app['env'] = 'production';
        $app['path.config'] = __DIR__ . '/config';
        $app['path.storage'] = __DIR__ . '/storage';

        // Filesystem
        $files = Mockery::mock(Filesystem::class);
        $app['files'] = $files;

        // View
        $finder = Mockery::mock('Illuminate\View\ViewFinderInterface');
        $finder->shouldReceive('addExtension');
        $app['view'] = new Factory(new EngineResolver, $finder, Mockery::mock(Dispatcher::class));

        // Config
        $defaults = include $this->root . '/../config/latte.php';
        $app['config'] = new Repository($config + $defaults);
        $app->bind('Illuminate\Config\Repository', fn () => $app['config']);

        return $app;
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
}
