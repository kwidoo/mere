<?php

namespace Kwidoo\Mere\Factories;

use Kwidoo\Mere\Contracts\Eventable;
use Illuminate\Contracts\Events\Dispatcher;

class LaravelEvents implements Eventable
{
    public function __construct(protected Dispatcher $dispatcher) {}

    public function dispatch(string $eventKey, mixed $context)
    {
        $this->dispatcher->dispatch($eventKey, ['data' => $context]);
    }
}
//
