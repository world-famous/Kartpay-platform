<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class MerchantOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->guard()->user()->type === "merchant")
        {
            return $next($request);
        }
    }

    protected function guard()
    {
        return Auth::guard('merchant');
    }
}
