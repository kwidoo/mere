<?php

namespace Kwidoo\Mere\Http\Middleware;

use Closure;

class BindResource
{
    public function handle($request, Closure $next)
    {
        $resource = $request->route('resource');

        $map = config('mere.resources');
        if (isset($map[$resource])) {
            app()->bind(\Kwidoo\Mere\Contracts\BaseService::class, $map[$resource]);
        }

        return $next($request);
    }
}
