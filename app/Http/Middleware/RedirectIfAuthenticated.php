<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth($guard)->check())
        {
            if ($guard == 'merchant')
            {
                return redirect()->route('merchants.dashboard.merchant');
            }
            return redirect()->route('admins.dashboard.panel');
        }
        return $next($request);
    }
}
