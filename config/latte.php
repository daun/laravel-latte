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
    | File extensions to accept for Latte files.
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
    | will use the standard path of the `view.compiled` config. Customize by
    | passing an absolute path to a different directory.
    |
    */

    'compiled' => null,

    /*
    |--------------------------------------------------------------------------
    | Default Layout
    |--------------------------------------------------------------------------
    |
    | Define a common parent layout used by all templates. Individual templates
    | no longer need to use the `{layout}` tag unless they want to override or
    | disable the layout entirely using `{layout none}`.
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
    | registered in the app. Define a custom class or disable by passing `null`.
    |
    */

    'translator' => \Daun\LaravelLatte\Extensions\LaravelTranslatorExtension::class,

    /*
    |--------------------------------------------------------------------------
    | Custom Latte Extensions
    |--------------------------------------------------------------------------
    |
    | Extensions that will be automatically registered with the Latte engine.
    | This is the best place to add custom filters and tags.
    |
    */

    'extensions' => [
        // \App\View\Latte\MyExtension::class,
    ],

];
