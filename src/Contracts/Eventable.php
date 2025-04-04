<?php

namespace Kwidoo\Mere\Contracts;

interface Eventable
{
    public function dispatch(string $eventKey, mixed $context);
}
