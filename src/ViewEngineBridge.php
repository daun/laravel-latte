<?php

namespace Daun\LaravelLatte;

use Illuminate\Contracts\View\Engine as ViewEngine;
use Latte\Engine as Latte;

class ViewEngineBridge implements ViewEngine
{
    public function __construct(
        protected Latte $latte
    ) {
    }

    public function get($path, array $data = []): string
    {
        return $this->latte->renderToString($path, $data);
    }
}
