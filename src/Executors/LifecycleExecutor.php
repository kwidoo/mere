<?php

namespace  Kwidoo\Mere\Executors;

use Kwidoo\Mere\Contracts\Eventable;
use Kwidoo\Mere\Contracts\Transactional;
use  Kwidoo\Mere\Contracts\Authorizer;
use  Kwidoo\Mere\Contracts\Lifecycle;

class LifecycleExecutor implements Lifecycle
{

    protected bool $eventsEnabled = true;
    protected bool $trxEnabled = true;
    protected bool $authEnabled = true;

    public function __construct(
        protected Authorizer $authorizer,
        protected Eventable $eventable,
        protected Transactional $trx
    ) {}

    /**
     * @param string $action
     * @param string $resource
     * @param mixed $context
     * @param callable $callback
     *
     * @return mixed
     */
    public function run(string $action, string $resource, mixed $context, callable $callback): mixed
    {
        if ($this->authEnabled) {
            $this->authorizer->authorize($action, $resource, $context);
        }

        $core = $this->trxEnabled
            ? fn() => $this->trx->run($callback)
            : $callback;

        return $this->eventsEnabled
            ? $this->eventable->dispatch("{$resource}.{$action}", $context, $core)
            : $core();
    }

    /**
     * @return static
     */
    public function withoutEvents(): static
    {
        $new = clone $this;

        $new->eventsEnabled = false;

        return $new;
    }

    /**
     * @return static
     */
    public function withoutTrx(): static
    {
        $new = clone $this;

        $new->trxEnabled = false;

        return $new;
    }

    /**
     * @return static
     */
    public function withoutAuth(): static
    {
        $new = clone $this;

        $new->authEnabled = false;

        return $new;
    }
}
