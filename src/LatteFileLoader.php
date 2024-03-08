<?php

namespace Daun\LaravelLatte;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewName;
use Latte\Loader;

class LatteFileLoader implements Loader
{
    public function __construct(
        protected Factory $view
    ) {
    }

    public function finder(): FileViewFinder
    {
        return $this->view->getFinder();
    }

    public function filesystem(): Filesystem
    {
        return $this->finder()->getFilesystem();
    }

    public function getContent(string $name): string
    {
        if ($this->fileExists($name)) {
            return $this->getFile($name);
        }

        $path = $this->findViewPath($name);

        if ($this->isExpired($path, time())) {
            if (@touch($path) === false) {
                trigger_error("File's modification time is in the future. Cannot update it: ".error_get_last()['message'], E_USER_WARNING);
            }
        }

        return $this->getFile($path);
    }

    public function isExpired(string $path, int $time): bool
    {
        try {
            $mtime = $this->filesystem()->lastModified($path);

            return $mtime > $time;
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

    protected function resolve(string $name, ?string $context): string
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

    protected function fileExists(string $name): string
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

        return implode(DIRECTORY_SEPARATOR, $res);
    }
}
