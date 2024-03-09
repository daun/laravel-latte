<?php

namespace TestExtensions;

use Latte\Extension;

class CustomExtension extends Extension
{
    public function getFilters(): array
    {
        return [
            'customFilter' => fn ($string) => "CUSTOM({$string})"
        ];
    }
}
