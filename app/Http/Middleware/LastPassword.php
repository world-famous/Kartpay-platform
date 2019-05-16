<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Guard;

use App\Libraries\Passwords;

class LastPassword
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $url = $request->getHost();
        $guard = "admin";

        if(preg_match('/merchant./',$url))
        {
            $guard = "merchant";
        }

        if(Passwords::requireChange($this->auth->guard($guard)->user()->id, $guard))
        {
            return redirect('password/change');
        }
        return $next($request);
    }
}
