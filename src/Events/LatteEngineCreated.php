<?php

namespace Daun\LaravelLatte\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Latte\Engine;

class LatteEngineCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Engine $engine
    ) {
    }
}
