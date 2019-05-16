<?php

namespace App\Http\Middleware;

use Closure;

class DbNotAlreadySetup
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
      //If db is not setup yet
        if(getLiveEnv('hasToSetupDb')==="0")
        {
            return redirect()->route('home');
        }
            return $next($request);
    }
}
