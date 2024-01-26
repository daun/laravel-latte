<?php

namespace Daun\LaravelLatte\Extensions;

use Latte\Essential\TranslatorExtension;
use Latte\Extension;

/**
 * Latte extension for using Laravel's translation stores.
 *
 * Proxies all calls to an underlying `TranslatorExtension` instance. This is only required
 * as Latte currently defines `TranslatorExtension` as final and we can't easily extend it.
 *
 * {(trans_key|translate)}                 or    {_'trans_key'}
 * {(trans_key|translate:$lang)}           or    {_'trans_key', $lang}
 * {(trans_key|translate:[some => data])}  or    {_'trans_key', [some => data]}
 */

class LaravelTranslatorExtension extends Extension
{
    protected TranslatorExtension $translator;

    public function __construct()
    {
        $this->translator = new TranslatorExtension([$this, 'translate']);
    }

    public function translate($key, $replace = [], $locale = null) {
        if (is_string($replace) && ! $locale) {
            $locale = $replace;
            $replace = [];
        }

        return trans($key, $replace, $locale);
    }

    public function translateChoice($key, $count, $replace = [], $locale = null) {
        return trans_choice($key, $count, $replace, $locale);
    }

    public function getFilters(): array
    {
        return [
            ...$this->translator->getFilters(),
            'translate' => fn(...$args) => $this->translate(...$args),
            'trans' => fn(...$args) => $this->translate(...$args),
            'transChoice' => fn(...$args) => $this->translateChoice(...$args),
        ];
    }

    public function getTags(): array
    {
        return $this->translator->getTags();
    }

    public static function toValue($args): mixed
    {
        return TranslatorExtension::toValue($args);
    }
}
