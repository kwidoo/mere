<?php

namespace Kwidoo\Mere\Factories;

use Kwidoo\Mere\Contracts\Authorizer;
use Kwidoo\Mere\Contracts\AuthorizerFactory as AuthorizerFactoryContract;

class AuthorizerFactory implements AuthorizerFactoryContract
{
    public function resolve(string $context): Authorizer
    {
        return match ($context) {
            default => app()->make(DefaultAuthorizer::class),
        };
    }
}
