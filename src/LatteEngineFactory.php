<?php

namespace Daun\LaravelLatte;

use Daun\LaravelLatte\Events\LatteEngineCreated;
use Daun\LaravelLatte\Extensions\LaravelTranslatorExtension;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use Latte\Engine;
use Latte\Extension;
use Latte\Loader;
use Latte\Runtime\Template;

class LatteEngineFactory
{
    public function __construct(
        protected Loader $loader,
        protected Repository $config
    ) {
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
        return collect($extensions)->map(fn ($class) => new $class());
    }

    protected function getTranslatorExtension(): ?Extension
    {
        $translator = $this->config->get('latte.translator');
        if (empty($translator)) {
            return null;
        } elseif (is_string($translator)) {
            return new $translator();
        } elseif (is_callable($translator)) {
            return $translator();
        } else {
            return new LaravelTranslatorExtension();
        }
    }
}
