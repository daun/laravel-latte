<?php

namespace Daun\LaravelLatte;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewFinderInterface;
use Illuminate\View\ViewName;
use Latte\Loader;

class LatteFileLoader implements Loader
{
    public function __construct(
        protected Factory $view
    ) {
    }

    public function finder(): ViewFinderInterface
    {
        return $this->view->getFinder();
    }

    public function filesystem(): Filesystem
    {
        $finder = $this->finder();
        if ($finder instanceof FileViewFinder) {
            return $finder->getFilesystem();
        } else {
            throw new \RuntimeException('Latte requires a file-based view finder.');
        }
    }

    public function extensions(): array
    {
        return collect($this->view->getExtensions())
            ->filter(fn ($engine) => $engine === 'latte')
            ->keys()
            ->all();
    }

    public function getContent(string $name): string
    {
        if ($this->fileExists($name)) {
            return $this->getFile($name);
        }

        return $this->getFile($this->resolve($name));
    }

    public function isExpired(string $path, int $time): bool
    {
        try {
            return $this->filesystem()->lastModified($path) > $time;
        } catch (\Throwable $th) {
            return true;
        }
    }

    public function getReferredName(string $name, string $referringFile): string
    {
        return $this->resolve($name, $referringFile);
    }

    public function getUniqueId(string $name): string
    {
        return strtr($name, '/', DIRECTORY_SEPARATOR);
    }

    protected function resolve(string $name, ?string $context = null): string
    {
        if ($this->looksLikePath($name)) {
            return $this->normalizePath($context ? "{$context}/../{$name}" : $name);
        } else {
            return $this->findViewPath($name);
        }
    }

    protected function looksLikePath(string $str): bool
    {
        return Str::startsWith($str, ['/', '../', './']);
    }

    protected function fileExists(string $name): bool
    {
        return $this->filesystem()->exists($name);
    }

    protected function getFile(string $name): string
    {
        return $this->filesystem()->get($name);
    }

    protected function findViewPath(string $name): string
    {
        $name = $this->normalizeViewName($name);

        return $this->finder()->find($name);
    }

    protected function normalizeViewName(string $name): string
    {
        return ViewName::normalize($name);
    }

    protected function normalizePath(string $path): string
    {
        $res = [];
        foreach (explode('/', strtr($path, '\\', '/')) as $part) {
            if ($part === '..' && $res && end($res) !== '..') {
                array_pop($res);
            } elseif ($part !== '.') {
                $res[] = $part;
            }
        }

        $path = implode(DIRECTORY_SEPARATOR, $res);

        if (! $this->fileExists($path)) {
            foreach ($this->extensions() as $extension) {
                if ($this->fileExists("{$path}.{$extension}")) {
                    return "{$path}.{$extension}";
                }
            }
        }

        return $path;
    }
}
