<?php

namespace Kwidoo\Mere\Resolvers;

use Kwidoo\Mere\Contracts\Authorizer;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class AuthAwareResolver
{
    public function __construct(
        protected AuthFactory $auth
    ) {}

    public function resolve(string $unauthenticated, ?string $authenticated = null, ?string $guard = null): Authorizer
    {
        $guardInstance = $guard
            ? $this->auth->guard($guard)
            : $this->auth->guard();

        return $guardInstance->check() && $authenticated
            ? app($authenticated)
            : app($unauthenticated);
    }
}
