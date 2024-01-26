# Laravel Latte

Add support for the [Latte](https://latte.nette.org) templating engine in [Laravel](https://laravel.com) views.

## Installation

``` bash
composer require daun/laravel-latte
```

## Usage

Installing the composer package will automatically register a Service Provider with your Laravel app.
You can now render Latte files like you would any other view. The example below will render the
view at `resources/views/home.latte` using Latte.

```php
Route::get('/', fn() => view('home'));
```

## Configuration

The package will read its configuration from `config/latte.php`. Use Artisan to publish and
customize the default config file:

```sh
php artisan vendor:publish --provider="Daun\LaravelLatte\ServiceProvider"
```

## Including partials

The package includes a custom loader that allows including partials from subdirectories using
Blade's dot notation to specify folders:

```latte
{* Will include /resources/views/partials/slideshow/image.latte *}

{include 'partials.slideshow.image'}
```

## Translations

The package includes a custom translator extension that acts as a bridge to Laravel's translation
service and allows using any translations registered in your app to be used.

```latte
{* Display a translation from /lang/en/messages.php *}

{_'messages.welcome'}
```

## Default layout file

If you require a common layout file for all views, you can define a default layout file.

```php
// Use /resources/views/layouts/default.latte as layout for all views
'default_layout' => 'layouts.default'
```

## Extending Latte

### Extensions

To extend Latte and add your own tags, filters and functions, you can use the `extensions` array
in `config/latte.php` to supply a list of Latte extension to register automatically:

```php
'extensions' => [
    \App\View\Latte\MyExtension::class
]
```

### Facade

Using the `Latte` facade, you can configure the Latte engine instance yourself.
Modify its config, add custom tags, filters, etc.

```php
use Daun\LaravelLatte\Facades\Latte;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Latte::addExtension(new MyCustomLatteExtension());
    }
}
```

### Events

Listen for a `LatteEngineCreated` event to customize the returned Latte instance:

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
