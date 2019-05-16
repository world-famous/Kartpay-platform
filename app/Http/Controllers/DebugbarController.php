<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class DebugbarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Enable or Disable Debug Mode
     *
    */
    public function changeDebugMode($value)
    {
        setEnv('APP_DEBUG', $value);
        if($value) setEnv('APP_ENV', 'local');
        else setEnv('APP_ENV', 'production');
    		Artisan::call('config:cache');
    		return 'Debugbar enabled: ' . $value;
    }
    /**
     * END Enable or Disable Debug Mode
     *
    */
}
