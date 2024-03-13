# Laravel Latte

[![Latest Version on Packagist](https://img.shields.io/packagist/v/daun/laravel-latte.svg)](https://packagist.org/packages/daun/laravel-latte)
[![Test Status](https://img.shields.io/github/actions/workflow/status/daun/laravel-latte/ci.yml?label=tests)](https://github.com/daun/laravel-latte/actions/workflows/ci.yml)
[![Code Coverage](https://img.shields.io/codecov/c/github/daun/laravel-latte)](https://app.codecov.io/gh/daun/laravel-latte)
[![License](https://img.shields.io/github/license/daun/laravel-latte.svg)](https://github.com/daun/laravel-latte/blob/master/LICENSE)

Add support for the [Latte](https://latte.nette.org) templating engine in [Laravel](https://laravel.com) views.

## Features

- Render `.latte` views
- Latte engine configurable via facade
- Translation provider to access localized messages
- Laravel-style path resolution when including partials
- Extensive test coverage

## Installation

``` bash
composer require daun/laravel-latte
```

## Requirements

- PHP 8.1+
- Laravel 9/10/11

## Usage

Installing the composer package will automatically register a Service Provider with your Laravel app.
You can now render Latte files like you would any other view. The example below will render the
view at `resources/views/home.latte` using Latte.

```php
Route::get('/', function() {
    return view('home');
});
```

## Configuration

The package will read its configuration from `config/latte.php`. Use Artisan to publish and
customize the default config file:

```sh
php artisan vendor:publish --provider="Daun\LaravelLatte\ServiceProvider"
```

## Localization

The package includes a custom translator extension that acts as a bridge to Laravel's translation
service. It allows using any translations registered in your app to be used in Latte views, using
either the `_` tag or the `translate` filter:

```latte
{_'messages.welcome'}
{('messages.welcome'|translate)}
```

You can pass in parameters as usual:

```latte
{_'messages.welcome', [name: 'Adam']}
{('messages.welcome'|translate:[name: 'Adam'])}
```

Translate using a custom locale by passing it after or in place of the params:

```latte
{_'messages.welcome', [name: 'Mary'], 'fr'}
{_'messages.welcome', 'fr'}
```

Pluralization works using the `transChoice` filter.

```latte
{('messages.apples'|transChoice:5)}
```

## Path resolution

The package includes a custom loader that allows including partials from subdirectories using
Laravel's dot notation to specify folders.

```latte
{* resolves to /resources/views/partials/slideshow/image.latte *}

{include 'partials.slideshow.image'}
```

To specifically include files relative to the current file, prefix the path with `./` or `../`:

```latte
{include './image.latte'}
```

## Default layout

If you require a common layout file for all views, you can define a default layout in
`config/latte.php`. Any views without a specifically set layout will now be merged into that layout.

```php
[
    // /resources/views/layouts/default.latte
    'default_layout' => 'layouts.default'
]
```

## Configuring Latte

### Extensions

To extend Latte and add your own tags, filters and functions, you can use the `extensions` array
in `config/latte.php` to supply a list of Latte extensions to register automatically.

```php
[
    'extensions' => [
        \App\View\Latte\MyExtension::class
    ]
]
```

### Facade

You can also directly access and configure the Latte engine instance from the `Latte` facade.
Modify its config, add custom filters, etc.

```php
use Daun\LaravelLatte\Facades\Latte;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Latte::addFilter('plural', fn($str) => Str::plural($str));
    }
}
```

### Events

If you need to be notified when the Latte engine is created, listen for the `LatteEngineCreated`
event to receive and customize the returned engine instance.

```php
use Daun\LaravelLatte\Events\LatteEngineCreated;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        Event::listen(function (LatteEngineCreated $event) {
            $event->engine->setAutoRefresh(true);
        });
    }
}
```

## License

[MIT](https://opensource.org/licenses/MIT)
