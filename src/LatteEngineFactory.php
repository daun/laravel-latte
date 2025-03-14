<?php

namespace Daun\LaravelLatte;

use Daun\LaravelLatte\Events\LatteEngineCreated;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Latte\Engine;
use Latte\Extension;
use Latte\Loader;
use Latte\Runtime\Template;

final class LatteEngineFactory
{
    public function __construct(
        protected Loader $loader,
        protected Repository $config
    ) {}

    public static function make(Loader $loader, Repository $config): Engine
    {
        $factory = new self($loader, $config);

        return $factory->create();
    }

    public function create(): Engine
    {
        $latte = new Engine;
        $latte->setLoader($this->loader);
        $latte->setTempDirectory($this->getCacheDirectory());
        $latte->setAutoRefresh($this->isDebug());

        if ($translator = $this->getTranslatorExtension()) {
            $latte->addExtension($translator);
        }

        foreach ($this->getUserExtensions() as $extension) {
            $latte->addExtension($extension);
        }

        if ($layout = $this->getDefaultLayout()) {
            $latte->addProvider('defaultLayout', fn () => $layout);
            $latte->addProvider('coreParentFinder', function (Template $template) {
                // ignore includes/embeds
                if ($template->getReferenceType()) {
                    return;
                }

                return ($template->global->defaultLayout)($template);
            });
        }

        // Allow customizing the engine after creation
        LatteEngineCreated::dispatch($latte);

        return $latte;
    }

    protected function isDebug(): bool
    {
        return (bool) $this->config->get('app.debug');
    }

    protected function getCacheDirectory(): ?string
    {
        return $this->config->get('latte.compiled') ?: $this->config->get('view.compiled') ?: null;
    }

    protected function getDefaultLayout(): ?string
    {
        return $this->config->get('latte.default_layout');
    }

    protected function getUserExtensions(): Collection
    {
        $extensions = $this->config->get('latte.extensions', []);

        return collect($extensions)->map(fn ($class) => new $class);
    }

    protected function getTranslatorExtension(): ?Extension
    {
        $translator = $this->config->get('latte.translator');
        if ($translator === null) {
            return null;
        } elseif (is_string($translator)) {
            return new $translator;
        } else {
            throw new \Exception('Invalid translator extension: must be class name or null.');
        }
    }
}
