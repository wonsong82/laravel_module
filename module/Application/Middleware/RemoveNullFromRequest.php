<?php

namespace Module\Application\Middleware;

use Closure;


class RemoveNullFromRequest
{
    /**
     * Remove null request so DB default can work
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        foreach($request->all() as $name => $value){
            if($request[$name] === null){
                unset($request[$name]);
            }
        }

        return $next($request);
    }
}
