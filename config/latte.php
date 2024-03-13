<?php

/**
 * Configuration options for the Latte templating engine.
 * Part of daun/laravel-latte package
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Extensions
    |--------------------------------------------------------------------------
    |
    | File extensions for Latte view files.
    |
    */

    'file_extensions' => [
        'latte.html',
        'latte',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compiled View Path
    |--------------------------------------------------------------------------
    |
    | Determines where compiled Latte templates will be stored. By default, this
    | just uses whatever is configured for standard Blade views.
    |
    */

    // 'compiled' => realpath(storage_path('framework/views/latte')),

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | Define a default layout file to be used by all template files. Useful if
    | you require a common layout file for all views.
    |
    */

    'default_layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Translator Extension
    |--------------------------------------------------------------------------
    |
    | The translator extension to use in Latte templates. The default acts as a
    | bridge to Laravel's translation service and allows using any translations
    | registered in your app to be used. Feel free to disable or customize.
    |
    */

    'translator' => \Daun\LaravelLatte\Extensions\LaravelTranslatorExtension::class,

    /*
    |--------------------------------------------------------------------------
    | Custom Latte Extensions
    |--------------------------------------------------------------------------
    |
    | Extensions that will be automatically registered with the Latte engine.
    | This is the best place to register custom filters and tags.
    |
    */

    'extensions' => [
        // \App\View\Latte\MyExtension::class,
    ],

];
