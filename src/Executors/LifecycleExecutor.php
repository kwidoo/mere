<?php

namespace  Kwidoo\Mere\Executors;

use Kwidoo\Mere\Contracts\Eventable;
use Kwidoo\Mere\Contracts\Transactional;
use Kwidoo\Mere\Contracts\Lifecycle;
use Kwidoo\Mere\Contracts\Loggable;
use Kwidoo\Mere\Contracts\AuthorizerFactory;

class LifecycleExecutor implements Lifecycle
{

    protected bool $eventsEnabled = true;
    protected bool $trxEnabled = true;
    protected bool $authEnabled = true;
    protected bool $loggingEnabled = true;

    public function __construct(
        protected AuthorizerFactory $authorizerFactory,
        protected Eventable $eventable,
        protected Transactional $trx,
        protected ?Loggable $logger,

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

        if ($this->logger && $this->loggingEnabled) {
            $this->logger->info("Running lifecycle for: {$resource}.{$action}", [
                'eventsEnabled' => $this->eventsEnabled,
                'trxEnabled' => $this->trxEnabled,
                'authEnabled' => $this->authEnabled,
                'context' => $context,
            ]);
        }

        $this->authorize($action, $resource, $context);

        return $this->handleRun($action, $resource, $context, $callback);
    }


    /**
     * @param string $action
     * @param string $resource
     * @param mixed $context
     *
     * @return void
     */
    protected function authorize(string $action, string $resource, mixed $context): void
    {
        if ($this->authEnabled) {
            $authorizer = $this->authorizerFactory->resolve($resource);
            $authorizer->authorize($action, $resource, $context);
        }
    }

    /**
     * @param string $action
     * @param string $resource
     * @param mixed $context
     * @param callable $callback
     *
     * @return mixed
     */
    protected function handleRun(string $action, string $resource, mixed $context, callable $callback): mixed
    {
        $eventKey = "{$resource}.{$action}";

        try {
            if ($this->eventsEnabled) {
                $this->eventable->dispatch("before.{$eventKey}", $context);
            }

            $core = fn() => $this->trxEnabled
                ? $this->trx->run($callback)
                : $callback();

            $result = $core();
            if ($this->eventsEnabled) {
                $this->eventable->dispatch("after.{$eventKey}", $result);
            }

            return $result;
        } catch (\Throwable $e) {
            if ($this->logger && $this->loggingEnabled) {
                $this->logger->error("Lifecycle encountered an error in {$eventKey}", [
                    'context' => $context,
                    'error' => $e->getMessage(),
                ]);
            }
            if ($this->eventsEnabled) {
                $this->eventable->dispatch("failed.{$eventKey}", [
                    'context' => $context,
                    'error' => $e->getMessage(),
                ]);
            }

            throw $e;
        }
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
