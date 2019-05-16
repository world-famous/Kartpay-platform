<?php

namespace App\Http\Middleware;

use Closure;

class CheckFirstTimeAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(getLiveEnv('hasToSetupDb')==1 || !strlen(getLiveEnv('hasToSetupDb')))
        {
            //either value is one
            //or empty means variable isn't present
            return redirect()->route('askDbCredentials');
        }
        return $next($request);
    }
}
