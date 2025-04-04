<?php

namespace Kwidoo\Mere\Contracts;

interface Loggable
{
    public function info(string $message, array $context = []): void;
    public function error(string $message, array $context = []): void;
    public function debug(string $message, array $context = []): void;
}
