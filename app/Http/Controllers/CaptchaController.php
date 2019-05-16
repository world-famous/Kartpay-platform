<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Response;
use Captcha;

class CaptchaController extends Controller
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
     * Do Refresh Captcha
     *
    */
    public function refreshCaptcha()
    {
      return Response::json(
  			[
  				'response' => 'success',
  				'image_url' => Captcha::src()
  			]
  		);
    }
    /**
     * END Do Refresh Captcha
     *
    */
}
