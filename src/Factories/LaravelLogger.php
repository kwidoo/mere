<?php

namespace Kwidoo\Mere\Factories;

use Illuminate\Support\Facades\Log;
use Kwidoo\Mere\Contracts\Loggable;

class LaravelLogger implements Loggable
{
    public function debug(string $message, array $context = []): void
    {
        Log::debug($message, $context);
    }

    public function info(string $message, array $context = []): void
    {
        Log::info($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        Log::error($message, $context);
    }
}
