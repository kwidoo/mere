<?php

namespace Kwidoo\Mere\Factories;

use Kwidoo\Mere\Contracts\Eventable;
use Illuminate\Contracts\Events\Dispatcher;

class LaravelEvents implements Eventable
{

    public function __construct(protected Dispatcher $dispatcher) {}

    public function dispatch(string $eventKey, mixed $context, callable $callback)
    {
        $this->dispatcher->dispatch('before.' . $eventKey, ['data' => $context]);

        try {
            $result = $callback();

            $this->dispatcher->dispatch('after.' . $eventKey, ['data' => $result]);

            return $result;
        } catch (\Throwable $e) {
            $this->dispatcher->dispatch('failed.' . $eventKey, ['data' => $context, 'error' => $e->getMessage()]);

            throw $e;
        }
    }
}
//
