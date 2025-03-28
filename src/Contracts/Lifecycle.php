<?php

namespace  Kwidoo\Mere\Contracts;

interface Lifecycle
{
    public function run(string $action, string $resource, mixed $context, callable $callback): mixed;

    public function withoutEvents(): static;

    public function withoutTrx(): static;

    public function withoutAuth(): static;
}
