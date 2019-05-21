<?php

namespace Module\Application\Middleware;

use Closure;

class SetLocale
{

    public function handle($request, Closure $next)
    {
        if(!!($user = auth()->user())){
            date_default_timezone_set($user->timezone);

            app()->setLocale($user->locale->code);
        }

        return $next($request);
    }
}
