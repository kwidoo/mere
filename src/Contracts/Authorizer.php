<?php

namespace  Kwidoo\Mere\Contracts;

interface Authorizer
{
    /**
     * Checks if the current user is authorized to perform an action on a resource.
     *
     * @param string $ability The ability to check.
     * @param mixed $resource The resource to check against.
     * @param array $extra Extra arguments to pass to the gate.
     * @return null
     */
    public function authorize(string $ability, mixed $resource, array $extra = []);
}
