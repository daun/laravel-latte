<?php

namespace Daun\LaravelLatte;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function provides(): array
    {
        return [
            'latte.engine',
            'latte.loader',
            'latte.bridge',
        ];
    }

    public function register(): void
    {
        $this->registerLatteFileLoader();
        $this->registerLatteInstance();
        $this->registerViewEngine();
    }

    public function boot()
    {
        $this->loadConfiguration();
        $this->addViewExtensions();
    }

    protected function loadConfiguration()
    {
        $configPath = __DIR__.'/../config/latte.php';
        $this->publishes([$configPath => config_path('latte.php')], 'config');
        $this->mergeConfigFrom($configPath, 'latte');
    }

    protected function registerLatteFileLoader(): void
    {
        $this->app->bind('latte.loader', function (Application $app) {
            return new LatteFileLoader($app['view']);
        });
    }

    protected function registerLatteInstance(): void
    {
        $this->app->singleton('latte.engine', function (Application $app) {
            return LatteEngineFactory::make($app['latte.loader'], $app['config']);
        });
    }

    protected function registerViewEngine()
    {
        $this->app->singleton('latte.bridge', function (Application $app) {
            return new ViewEngineBridge($app['latte.engine']);
        });

        $this->app->extend('view.engine.resolver', function ($resolver) {
            $resolver->register('latte', fn () => $this->app['latte.bridge']);

            return $resolver;
        });
    }

    protected function addViewExtensions()
    {
        $extensions = $this->app['config']->get('latte.file_extensions', []);
        foreach ($extensions as $extension) {
            $this->app['view']->addExtension($extension, 'latte');
        }
    }
}
