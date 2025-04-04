<?php

namespace Kwidoo\Mere\Factories;

use  Kwidoo\Mere\Contracts\Authorizer;
use Spatie\LaravelData\Data;

class DefaultAuthorizer implements Authorizer
{
    public function authorize(string $ability, mixed $resource,  Data|array|null $extra = null)
    {
        return;
    }
}
