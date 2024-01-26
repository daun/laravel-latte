<?php

namespace Daun\LaravelLatte\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void addExtension(\Latte\Extension $extension)
 * @method static void addFilter(string $name, callable $callback)
 * @method static void addFilterLoader(callable $callback)
 * @method static void addFunction(string $name, callable $callback)
 * @method static void addProvider(string $name, mixed $value)
 * @see \Latte\Engine
 */
class Latte extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'latte.engine';
    }
}
