use Mere\Contracts\Authorizer;
use Mere\Contracts\Request;

<?php

namespace Kwidoo\Mere\Factories;

use  Kwidoo\Mere\Contracts\Authorizer;

class DefaultAuthorizer implements Authorizer
{
    public function authorize(string $ability, mixed $resource, array $extra = [])
    {
        return;
    }
}
