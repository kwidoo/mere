<?php

namespace Kwidoo\Mere\Http\Middleware;

use Closure;
use Kwidoo\Mere\Contracts\BaseService;
use RuntimeException;

class BindResource
{
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $resource = $route?->parameter('resource');
        $resourceMap = config('mere.resources');

        if (app()->runningInConsole() && !$resource && !empty($resourceMap)) {
            $resource = array_key_first($resourceMap);
        }

        if (!isset($resourceMap[$resource])) {
            throw new RuntimeException("Resource `{$resource}` is not registered in config(mere.resources).");
        }

        app()->bind(BaseService::class, fn() => app()->make($resourceMap[$resource]));
        return $next($request);
    }
}
