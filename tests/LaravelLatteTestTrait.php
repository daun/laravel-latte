<?php

namespace Daun\LaravelLatte\Tests;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Mockery as m;
use Daun\LaravelLatte\ServiceProvider;

trait LaravelLatteTestTrait
{
    protected $laravelLatteRoot;

    public function setUp(): void
    {
        $this->laravelLatteRoot = realpath(__DIR__ . '/../src');
    }

    public function tearDown(): void
    {
        m::close();
    }

    /**
     * @param array $customConfig that will override the default config.
     *      A recursive merge is applied except for $customConfig['extensions'] which
     *      will replace the whole 'extensions' if present
     * @return Application
     */
    protected function getApplication(array $customConfig = [])
    {
        $app = new Application(__DIR__);

        $app['env'] = 'production';
        $app['path.config'] = __DIR__ . '/config';
        $app['path.storage'] = __DIR__ . '/storage';

        // Filesystem
        $files = m::mock('Illuminate\Filesystem\Filesystem');
        $app['files'] = $files;

        // View
        $finder = m::mock('Illuminate\View\ViewFinderInterface');
        $finder->shouldReceive('addExtension');

        $app['view'] = new Factory(
            new EngineResolver,
            $finder,
            m::mock('Illuminate\Events\Dispatcher')
        );

        // Config
        $config = include $this->laravelLatteRoot . '/../config/latte.php';
        $config = array_merge($config, $customConfig);
        $app['config'] = new Repository($config);

        $app->bind('Illuminate\Config\Repository', function () use ($app) {
            return $app['config'];
        });

        return $app;
    }

    protected function addBridgeServiceToApplication(Application $app)
    {
        $provider = new ServiceProvider($app);
        $provider->register();
        $provider->boot();
    }
}
