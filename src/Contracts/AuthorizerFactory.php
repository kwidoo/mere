<?php

namespace  Kwidoo\Mere\Contracts;

interface AuthorizerFactory
{
    /**
     * Resolves an authorizer instance based on the given context.
     *
     * @param string $context The context for which to resolve the authorizer.
     * @return Authorizer The resolved authorizer instance.
     */
    public function resolve(string $context): Authorizer;
}
