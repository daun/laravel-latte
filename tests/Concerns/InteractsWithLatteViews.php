<?php

namespace Tests\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Testing\TestView;

trait InteractsWithLatteViews
{
    /**
     * Render the contents of the given Latte template string.
     */
    protected function latte(string $template, Arrayable|array $data = []): TestView
    {
        $tempDirectory = sys_get_temp_dir();

        if (! in_array($tempDirectory, ViewFacade::getFinder()->getPaths())) {
            ViewFacade::addLocation(sys_get_temp_dir());
        }

        $tempFileInfo = pathinfo(tempnam($tempDirectory, 'laravel-latte'));

        $tempFile = $tempFileInfo['dirname'].'/'.$tempFileInfo['filename'].'.latte';

        file_put_contents($tempFile, $template);

        return new TestView(view($tempFileInfo['filename'], $data));
    }
}
