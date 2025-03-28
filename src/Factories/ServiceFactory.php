<?php

namespace Kwidoo\Mere\Factories;

use Kwidoo\Mere\Contracts\BaseService;
use RuntimeException;

class ServiceFactory
{
    public static function make($resource): BaseService
    {
        $resourceMap = config('mere.resources');

        if (!isset($resourceMap[$resource])) {
            throw new RuntimeException("Resource `{$resource}` is not registered in config(mere.resources).");
        }

        return app()->make($resourceMap[$resource]);
    }
}
