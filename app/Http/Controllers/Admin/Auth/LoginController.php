<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Session;
use Carbon\Carbon;
use Firewall;
use DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class LoginController extends RegisterController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;
    use LogsActivity;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo      = '/dashboard'; //merchant.pg-application.dev;
    protected $redirectToPanel = '/panel'; //panel.pg-application.dev;

    /**
     * Log Event
     *
     * @var string
     */
    protected $eventLogin  = 'Login';
    protected $eventLogout = 'Logout';

    /**
     * Log Description
     *
     * @var string
     */
    protected $loginSuccess           = 'User logged in successfuly';
    protected $logoutSuccess          = 'User logged out successfuly';
    protected $loginOTP               = 'User try to login with correct credentials, system send OTP';
    protected $resendOTP              = 'User request to resend new OTP';
    protected $resendSecretLink       = 'User request to resend login secret link to email';
    protected $verificationOtpSuccess = 'User verified by OTP successfully';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Override authenticated Laravel method
     *
     */
    protected function authenticated($request, $user)
    {
  		$user->request_otp_times = '0';
  		$user->last_send_otp = null;
  		$user->last_send_email_secret_login = null;
  		$user->allow_login = '1';
  		$user->ip_address = \Request::ip();
  		$user->session_id = \Session::getId();
  		$user->save();

      //ACTIVITY LOG
      activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->loginSuccess);
      //END ACTIVITY LOG

      $this->limiter()->clear($request->ip());
      $this->clearLoginAttempts($request);
      return redirect()->route('admins.dashboard.panel');
    }
    /**
     * END Override authenticated Laravel method
     *
     */

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (cache()->has('login_admin') && cache()->get('login_admin') != $request->ip())
        {
            $errors = [$this->username() => 'User currently connected from another ip.'];

            if ($request->expectsJson())
            {
                return response()->json($errors, 401);
            }

            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors($errors);
        }

        if (!$this->guard()->validate($request->only('email', 'password')))
        {
            $this->limiter()->hit($request->ip(), 1 * 60 * 24 * 365 * 10);
        }

        if ($this->limiter()->attempts($request->ip()) >= 10)
        {
            $this->limiter()->clear($request->ip());
            $this->clearLoginAttempts($request);
            Firewall::blacklist($request->ip(), true);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $user = User::where('email', '=', $request->email)->first();

        if ($this->hasTooManyLoginAttempts($request) || ($user && $user->is_blocked && $user->type == 'staff'))
        {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

    		if(!is_null($user))
    		{
    			if($user->allow_login == '0' && $user->type == 'staff')
    			{
    				$this->guard()->logout();
    				// $request->session()->flush();
    				$request->session()->regenerate();
    				return view('auth.account_blocked');
    			}
    		}

        if ($this->attemptLogin($request))
        {
    			// START Check session if exist

    			$oldUser = User::find($user->id);

    			if($oldUser->session_id != null)
    			{
    				$sessions = Session::where('id', '=', $oldUser->session_id)->get();
    				foreach($sessions as $session)
    				{
    					if($session->ip_address != \Request::ip())
    					{
    						$timeLeft = Carbon::now()->timestamp - $session->last_activity; //find different seconds
    						if($timeLeft <= 60) // 60 seconds
    						{
    							$this->guard()->logout();

    							$errors = [$this->username() => 'Please wait for 5 min to re-login if your earlier browser session is closed'];
    							return redirect()->back()
    									->withInput($request->only($this->username(), 'remember'))
    									->withErrors($errors);
    						}
    					}
              else
              {
                $timeLeft = Carbon::now()->timestamp - $session->last_activity; //find different seconds
    						if($timeLeft <= 60) // 60 seconds
    						{
                  $this->guard()->logout();

                  $errors = [$this->username() => 'User currently connected from another session'];

                  return redirect()->back()
                      ->withInput($request->only($this->username(), 'remember'))
                      ->withErrors($errors);
                }
              }
    				}
    			}
    			// END Check Session

          if (getLiveEnv('OTP_SETTING') == '0')
          {
	          //first admin login
            if ($user->is_active == 0)
            {
              //auth('admin')->login($user);
    					$user->verification_code = $this->checkUniqueVerificationCode();
    					$user->save();
    					$verification_code = $user->verification_code;
              $type = $user->type;
			        $this->guard()->logout();
					    $request->session()->regenerate();

			        return redirect('/register?verification_code=' . $verification_code . '&type=' . $type);
            }
            return $this->sendLoginResponse($request);
          }
          else
          {
				      //first admin login
              if ($user->is_active == 0)
              {
      					$user->verification_code = $this->checkUniqueVerificationCode();
      					$user->save();
      					$verification_code = $user->verification_code;
                $type = $user->type;
      					$this->guard()->logout();
      					$request->session()->regenerate();

      					return redirect('/register?verification_code=' . $verification_code . '&type=' . $type);
              }
              $this->guard()->logout();
              $request->session()->regenerate();
              $server    = explode('.', $_SERVER['HTTP_HOST']);
              $subdomain = $server[0];

              if($user->allow_login == '1') $user->verification_code = $this->checkUniqueVerificationCode();
              $user->otp               = $this->checkUniqueOtp();
              $user->last_send_otp     = Carbon::now();
              $user->save();

		          $allowLogin = $user->allow_login;
		          if($allowLogin == '1' && $user->request_otp_times < '3')
		          {
      					//Send OTP
      					$this->SMSSend($user->contact_no, 'Your OTP number is: ' . $user->otp, false, getLiveEnv('SMS_USERNAME'), getLiveEnv('SMS_PASSWORD'), getLiveEnv('SMS_SENDER'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
      					//End send OTP

      					//ACTIVITY LOG
      					activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->loginOTP);
      					//END ACTIVITY LOG

      					$user->request_otp_times = $user->request_otp_times + 1;
      					$user->save();
		          }
              return redirect('/verification/otp_login?verification_code=' . $user->verification_code);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Load Verification OTP Page
     *
     */
    public function verificationOtpPage(Request $request)
    {
        $user = User::where('verification_code', '=', $request->verification_code)->first();

    		//check allow resend otp
    		$last_send_otp = Carbon::parse($user->last_send_otp);
    		$remainingTime = $last_send_otp->diffInSeconds(Carbon::now());
    		$allowLogin = $user->allow_login;

    		//check expired email secret link
    		$expiredSecretLink = '0';
    		if($user->last_send_email_secret_login != null)
    		{
    			$last_send_email_secret_login = Carbon::parse($user->last_send_email_secret_login);
    			$remainingTimeEmail = $last_send_email_secret_login->diffInSeconds(Carbon::now());
    			if($remainingTimeEmail > 3600) $expiredSecretLink = '1';
    		}

    		$requestOtpTimes = $user->request_otp_times;
            return view('auth.verification_otp_login', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
    }
    /**
     * END Load Verification OTP Page
     *
     */

     /**
      * Do Verification OTP
      *
      */
    public function verificationOtp(Request $request)
    {
        $users = User::where('otp', '=', $request->otp)
            ->where('updated_at', '>', Carbon::now()->subMinutes(5))
            ->get();

        if (count($users) == 0) {
            $user = User::where('verification_code', '=', $request->verification_code)->first();

  			$last_send_otp = Carbon::parse($user->last_send_otp);
  			$remainingTime = $last_send_otp->diffInSeconds(Carbon::now());

  			$allowLogin = $user->allow_login;

  			//check expired email secret link
  			$expiredSecretLink = '0';
  			if($user->last_send_email_secret_login != null)
  			{
  				$last_send_email_secret_login = Carbon::parse($user->last_send_email_secret_login);
  				$remainingTimeEmail = $last_send_email_secret_login->diffInSeconds(Carbon::now());
  				if($remainingTimeEmail > 3600) $expiredSecretLink = '1';
  			}

	      $requestOtpTimes = $user->request_otp_times;

        return view('auth.verification_otp_login', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'))->withErrors(new \Illuminate\Support\MessageBag(['otp' => 'OTP is not valid.']));
        }

        else
        {
            foreach ($users as $user)
            {
                $user->otp = $this->checkUniqueOtp();
                $user->save();
                Auth::guard('admin')->login($user);
                //ACTIVITY LOG
                activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->verificationOtpSuccess);
                activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->loginSuccess);
                //END ACTIVITY LOG
                return $this->authenticated($request, $user);
            }
        }
    }
    /**
     * Do END Verification OTP
     *
     */

     /**
      * Resend OTP
      *
      */
    public function resendOtp(Request $request)
    {
        $user = User::where('verification_code', '=', $request->verification_code)->first();
        $last_send_otp = Carbon::parse($user->last_send_otp);
	      $remainingTime = $last_send_otp->diffInSeconds(Carbon::now());

    		if($remainingTime > 60)
    		{
    			if($user->request_otp_times == '3')
    			{
    				$user->verification_code = $this->checkUniqueVerificationCode();
    				$user->allow_login = 0;
    				$user->last_send_email_secret_login = Carbon::now();
    				$user->save();

    				//Send Email Secret Link
    				$this->send_verification_email($user->name, $user->email, $user->name, 'Secret Link for login from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), url('/verification/login/' . $user->verification_code), 'Here is the link to complete the login:');
    				//End Send Email Secret Link

    				//ACTIVITY LOG
    				activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->resendSecretLink);
    				//END ACTIVITY LOG
    			}
    			else
    			{
    				$user->otp = $this->checkUniqueOtp();
    				$user->request_otp_times = $user->request_otp_times + 1;
    				$user->last_send_otp = Carbon::now();
    				$user->save();

    				//Send OTP
    				$this->SMSSend($user->contact_no, 'Your OTP number is: ' . $user->otp, false, getLiveEnv('SMS_USERNAME'), getLiveEnv('SMS_PASSWORD'), getLiveEnv('SMS_SENDER'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
    				//End send OTP

    				//ACTIVITY LOG
    				activity($this->eventLogin)->causedBy($user)->withProperties(['email' => $user->email])->log($this->resendOTP);
    				//END ACTIVITY LOG
    			}
    		}

    		$last_send_otp = Carbon::parse($user->last_send_otp);
    		$remainingTime = $last_send_otp->diffInSeconds(Carbon::now());

		    $allowLogin = $user->allow_login;

    		//check expired email secret link
    		$expiredSecretLink = '0';
    		if($user->last_send_email_secret_login != null)
    		{
    			$last_send_email_secret_login = Carbon::parse($user->last_send_email_secret_login);
    			$remainingTimeEmail = $last_send_email_secret_login->diffInSeconds(Carbon::now());
    			if($remainingTimeEmail > 3600) $expiredSecretLink = '1';
    		}

    		$requestOtpTimes = $user->request_otp_times;

        return view('auth.verification_otp_login', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
    }
    /**
     * END Resend OTP
     *
     */

     /**
      * Do Verification Email Login by Secret Email (This secret email will sent if OTP failed more than 3 times)
      *
      */
  	public function verificationEmailLogin($verification_code)
  	{
  		$users = User::where('verification_code', '=', $verification_code)
  				->where('last_send_email_secret_login', '>', Carbon::now()->subHours(24))
  				->get();

  		if(count($users) > 0)
  		{
  			foreach($users as $user)
  			{
  				$user->verification_code = $this->checkUniqueVerificationCode();
  				$user->request_otp_times = '0';
  				$user->last_send_otp = null;
  				$user->last_send_email_secret_login = null;
  				$user->allow_login = '1';
  				$user->save();

  				auth('admin')->login($user);
  				return redirect(url('/'));
  			}
  		}
  		else
  		{
  			//NOT FOUND
  			$requestOtpTimes = '3';
  			$expiredSecretLink = '1';
  			$remainingTime = 0;
  			$allowLogin = '0';
  			return view('auth.verification_otp_login', compact('remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
  		}
  	}
    /**
     * END Do Verification Email Login by Secret Email (This secret email will sent if OTP failed more than 3 times)
     *
     */

    /**
     * Log the user out of the application.
     * Override Laravel logout function.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();

        DB::transaction(function () use (&$user)
        {
            DB::table('users')->where('id', $user->id)->update(['ip_address' => null, 'session_id' => null]);
        }, 5);

        //ACTIVITY LOG
        activity($this->eventLogout)->causedBy($user)->withProperties(['email' => $user->email])->log($this->logoutSuccess);
        //END ACTIVITY LOG

        cache()->forget('login_admin');
        $this->guard()->logout();

        // $request->session()->flush();

        $request->session()->regenerate();

        $protocol = "http";
        if($request->secure())
        {
            $protocol = "https";
        }

        return redirect($protocol . '://panel' . getLiveEnv('SESSION_DOMAIN') . '/login');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 6, 60 * 6
        );
    }

    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $time = Carbon::now()->addSeconds($seconds)->diffForHumans();

        $message = Lang::get('auth.throttle', ['time' => $time]);

        $errors = [$this->username() => $message];

        if ($request->expectsJson()) {
            return response()->json($errors, 423);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $errors = [$this->username() => trans('auth.failed.attempt', ['attempt' => 6])];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
