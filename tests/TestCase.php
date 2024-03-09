<?php

namespace Tests;

use Daun\LaravelLatte\ServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Tests\Concerns\InteractsWithLatteViews;

abstract class TestCase extends LaravelTestCase
{
    use CreatesApplication;
    use InteractsWithLatteViews;

    protected $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createServiceProvider();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    protected function defineEnvironment(Application $app)
    {
        $app['path.lang'] = __DIR__.'/fixtures/lang';

        $defaults = include __DIR__ . '/../config/latte.php';
        tap($app['config'], function (Repository $config) use ($defaults) {
            $config->set('latte', $defaults);
            $config->set('view.paths', [__DIR__.'/fixtures/views']);
        });
    }

    public function modifyConfig(string $key, mixed $data)
    {
        tap($this->app['config'], function (Repository $config) use ($key, $data) {
            $data = is_callable($data) ? $data($config->get($key)) : $data;
            $config->set($key, $data);
        });
    }

    protected function createServiceProvider()
    {
        $this->provider = new ServiceProvider($this->app);
    }

    protected function bootServiceProvider()
    {
        if (! $this->provider) {
            $this->createServiceProvider();
        }
        $this->provider->register();
        $this->provider->boot();
    }
}
