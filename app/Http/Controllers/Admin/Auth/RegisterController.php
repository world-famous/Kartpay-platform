<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;
  	use LogsActivity;

  	/**
       * Log Event
       *
       * @var string
       */
  	protected $eventRegister = 'Register';
  	protected $eventUpdate = 'Update';
  	protected $eventLogout = 'Logout';

  	/**
       * Log Description
       *
       * @var string
       */
  	protected $registerNewUser = 'User created successfuly';
  	protected $userUpdated = 'User updated successfuly';
  	protected $verificationEmailSuccess = 'User verified by email successfully';
  	protected $resendEmailVerification = 'User request to resend new email verification';
  	protected $resendOTP = 'User request to resend new OTP';
  	protected $verificationOtpSuccess = 'User verified by OTP successfully';
    protected $resendSecretLink       = 'User request to send registration secret link to email';
	   protected $createPasswordSuccess = 'User create new password successfully';

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/verification';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'   => 'required|max:255',
            'last_name'    => 'required|max:255',
            'email'        => 'required|email|max:255|unique:users',
            'country_code' => 'required|min:3',
            'contact_no'   => 'required|max:10',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'             => $data['email'],
            'password'          => '',
            'country_code'      => $data['country_code'],
            'contact_no'        => $data['contact_no'],
            'verification_code' => $this->checkUniqueVerificationCode(),
			'last_send_email_secret_login' => Carbon::now(),
        ]);
    }

    /**
     * View Register Form if verification_code is valid
     *
     */
  	public function viewRegister(Request $request)
  	{
  		$users = User::where('verification_code', '=', $request->verification_code)->get();
  		if (count($users) > 0) {
  			foreach($users as $user)
  			{
  				$type = $user->type;
  				$verification_code = $user->verification_code;
  				$user_type = 'admin';
  				return view('auth.register', compact('type', 'verification_code', 'user_type'));
  			}
  		}
  		else
  		{
  			$type = 'panel';
  			return view('auth.register_not_found');
  		}
  	}
    /**
     * END View Register Form if verification_code is valid
     *
     */

     /**
      * Do Process Register new User
      *
      */
    protected function register(Request $request)
    {
  		if($request->type == 'panel') $this->validator($request->all())->validate();
  		else if($request->type == 'staff')
  		{
  			$this->validate($request, [
  				'first_name'   => 'required|max:255',
  				'last_name'    => 'required|max:255',
  				'country_code' => 'required|min:3',
  				'contact_no'   => 'required|max:10',
  			]);
	    }

	    $users = User::where('verification_code', '=', $request->verification_code)->get();

      if (count($users) == 0)
      {
        event(new Registered($user = $this->create($request->all())));

  			//ACTIVITY LOG
  			activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'country_code' => $user->country_code, 'contact_no' => $user->contact_no, 'verification_code' => $user->verification_code])->log($this->registerNewUser);
  			//END ACTIVITY LOG
        }
        else
        {
      		foreach($users as $user)
      		{
      			if($user->type == 'panel')
      			{
      				$user->email             = $request->email;
      			}
      			$user->name              = $request->first_name . ' ' . $request->last_name;
      			$user->first_name        = $request->first_name;
      			$user->last_name         = $request->last_name;
      			$user->country_code      = $request->country_code;
      			$user->contact_no        = $request->contact_no;
      			$user->verification_code = $this->checkUniqueVerificationCode();
      			$user->last_send_email_secret_login = Carbon::now();
      			$user->save();

      			//ACTIVITY LOG
      			activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email, 'first_name' => $user->first_name, 'last_name' => $user->last_name, 'country_code' => $user->country_code, 'contact_no' => $user->contact_no, 'verification_code' => $user->verification_code])->log($this->registerNewUser);
      			//END ACTIVITY LOG
      		}
        }

    		if($user->type == 'panel')
    		{
    			$user = User::where('email', '=', $request->email)->first();
    			$this->send_verification_email($user->name, $request->email, $user->name, 'Registration Email Verification from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), url('/verification/' . $user->verification_code), 'Here is the link to complete registration:');

    			return redirect(url('verification'))->with('email', $request->email);
    		}
    		else if($user->type == 'staff')
    		{
    			return redirect(url('/verification') . '/' . $user->verification_code);
    		}
    }
    /**
     * END Do Process Register new User
     *
     */

     /**
      * Do Process Verification Email
      *
      */
    public function verificationEmail($verification_code)
    {
        $users = User::where('verification_code', '=', $verification_code)
				->where('last_send_email_secret_login', '>', Carbon::now()->subHours(1))
				->get();

        if (count($users) == 0)
        {
            return view('auth.create_password');
        }
        else
        {
            foreach ($users as $user)
            {
      				//ACTIVITY LOG
      				activity($this->eventRegister)->causedBy($user)->withProperties(['verification_code' => $user->verification_code])->log($this->verificationEmailSuccess);
      				//END ACTIVITY LOG

      				$user->verification_code = $this->checkUniqueVerificationCode();
      				$user->request_otp_times = '0';
      				$user->last_send_otp = null;
      				$user->save();
              return view('auth.create_password', compact('user'));
            }
        }
    }
    /**
     * END Do Process Verification Email
     *
     */

     /**
      * Resend Email Verification
      *
      */
    public function resendLink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $users = User::where('email', '=', $request->email)->get();

        if (count($users) == 0)
        {
            return view('auth.resend_link')->withErrors(new \Illuminate\Support\MessageBag(['email' => 'Email not found on the system.']));
        }
        else
        {
            User::where('email', '=', $request->email)->update(['verification_code' => $this->checkUniqueVerificationCode(), 'last_send_email_secret_login' => Carbon::now()]);

            $user = User::where('email', '=', $request->email)->first();
            $this->send_verification_email($user->name, $request->email, $user->name, 'Registration Email Verification from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), url('/verification/' . $user->verification_code), 'Here is the link to complete registration:');

      			//ACTIVITY LOG
      			activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email])->log($this->resendEmailVerification);
      			//END ACTIVITY LOG

            return redirect($this->redirectPath())->with('email', $request->email);
        }
    }
    /**
     * END Resend Email Verification
     *
     */

     /**
      * Do OTP Verification
      *
      */
    public function verificationOtp(Request $request)
    {
        $users = User::where('otp', '=', $request->otp)
            ->where('last_send_otp', '>', Carbon::now()->subMinutes(5))
            ->get();

        if (count($users) == 0)
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

          return view('auth.verification_otp', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'))->withErrors(new \Illuminate\Support\MessageBag(['otp' => 'OTP is not valid.']));
        }
        else
        {
          foreach ($users as $user)
          {
    				//ACTIVITY LOG
    				activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email, 'otp' => $user->otp])->log($this->verificationOtpSuccess);
    				//END ACTIVITY LOG

    				$user->otp = $this->checkUniqueOtp();
    				$user->last_send_otp = null;
            $user->is_active = '1';
            $user->save();

            return redirect(route('admins.complete_registration'));
          }
        }
    }
    /**
     * END Do OTP Verification
     *
     */

     /**
      * Do Resend OTP Verification
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
  				$this->send_verification_email($user->name, $user->email, $user->name, 'Secret Link for login from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), url('/verification/register/' . $user->verification_code), 'Here is the link to complete the registration:');
  				//End Send Email Secret Link

  				//ACTIVITY LOG
  				activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email])->log($this->resendSecretLink);
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
  				activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email])->log($this->resendOTP);
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

      return view('auth.verification_otp', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
    }
    /**
     * END Do Resend OTP Verification
     *
     */

     /**
      * Process Create Password
      *
      */
    public function createPassword(Request $request)
    {
      $rules = array(
          'new_password'     => 'required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/',
          'confirm_password' => 'required|same:new_password|min:8|max:16',
      );

      $input = Input::all();

      $validation = Validator::make($input, $rules);

      if ($validation->fails())
      {
          return redirect('/verification/' . $request->verification_code)->withErrors($validation);
      }

      $user = User::where('verification_code', '=', $request->verification_code)->first();
  		$user->password = bcrypt($request->new_password);
  		$user->step_registration = 'otp';
  		$user->allow_login = 1;
      $user->save();

  		$last_send_otp = Carbon::parse($user->last_send_otp);
  		$remainingTime = $last_send_otp->diffInSeconds(Carbon::now());

  		if($remainingTime > 60 || $user->last_send_otp == null)
  		{
  			if($user->request_otp_times < '3')
  			{
  				$user->otp               = $this->checkUniqueOtp();
                  $user->request_otp_times = $user->request_otp_times + 1;
                  $user->last_send_otp     = Carbon::now();
                  $user->save();

			            //ACTIVITY LOG
                  activity($this->eventRegister)->causedBy($user)->withProperties(['email' => $user->email, 'verification_code' => $user->verification_code])->log($this->createPasswordSuccess);
                  //END ACTIVITY LOG

                  //Send OTP
                  $this->SMSSend($user->contact_no, 'Your OTP number is: ' . $user->otp, false, config('sms.username'), config('sms.password'), config('sms.sender'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
                  //End send OTP
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

      return view('auth.verification_otp', compact('user', 'remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
    }
    /**
     * END Process Create Password
     *
     */

     /**
      * Do Email Verification
      *
      */
  	public function verificationEmailRegistration($verification_code)
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
  				$user->is_active = '1';
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
  			return view('auth.verification_otp', compact('remainingTime', 'allowLogin', 'expiredSecretLink', 'requestOtpTimes'));
  		}
  	}
    /**
     * END Do Email Verification
     *
     */

     /**
      * Process Staff Registration
      *
      */
  	public function staffRegistration($verification_code, Request $request)
  	{
  		$users = User::where('verification_code', '=', $verification_code)->get();

  		if(count($users) == 0)
  		{
  			//NOT FOUND
  			return view('auth.register_not_found');
  		}
  		else
  		{
  			foreach($users as $user)
  			{
  				$user->verification_code = $this->checkUniqueVerificationCode();
  				$user->save();

  				$type = $user->type;
  				$this->guard()->logout();
  				$request->session()->regenerate();

  				return redirect('/register?verification_code=' . $user->verification_code . '&type=' . $type);
  			}
  		}
  	}
    /**
     * END Process Staff Registration
     *
     */

     /**
      * Complete Registration
      *
      */
  	public function completeRegistration()
  	{
  		return view('auth.registration_complete_panel');
  	}
    /**
     * END Process Staff Registration
     *
     */

     /**
      * Create Random String
      * @param length (integer)
      * @return string
      */
    public function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $string = '';
        $max    = strlen($keyspace) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $keyspace[mt_rand(0, $max)];
        }
        return $string;
    }
    /**
     * END Create Random String
     *
     */

     /**
      * Create Random OTP
      * @param length (integer)
      * @return string
      */
  	public function random_otp($length, $keyspace = '0123456789')
    {
        $string = '';
        $max    = strlen($keyspace) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $keyspace[mt_rand(0, $max)];
        }
        return $string;
    }
    /**
     * END Create Random OTP
     *
     */

     /**
      * Get Unique OTP
      * @return string
      */
  	function checkUniqueOtp()
  	{
  		$unique = false;
  		$otp = $this->random_otp(6);
  		while(!$unique)
  		{
  			$users = User::where('otp', '=', $otp)->get();
  			if(count($users) > 0)
  			{
  				$otp = $this->random_otp(6);
  			}
  			else
  			{
  				$unique = true;
  				break;
  			}
  		}
  		return $otp;
  	}
    /**
     * END Get Unique OTP
     *
     */

     /**
      * Get Unique Verification Code
      * @return string
      */
  	function checkUniqueVerificationCode()
  	{
  		$unique = false;
  		$verification_code = $this->random_str(50);
  		while(!$unique)
  		{
  			$users = User::where('verification_code', '=', $verification_code)->get();
  			if(count($users) > 0)
  			{
  				$verification_code = $this->random_str(50);
  			}
  			else
  			{
  				$unique = true;
  				break;
  			}
  		}
  		return $verification_code;
  	}
    /**
     * END Get Unique Verification Code
     * @return string
     */

     /**
      * Send Verification Email
      * @param name (string), recipient_mail (string), recipient_name (string), subject (string), sender_email (string), sender_name (string), url (url - ), body (html format)
      * @return string
      */
    public function send_verification_email($name, $recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name, $url, $body)
    {
        $data = array('name' => $name, 'url' => $url, 'body' => $body);
        Mail::send('auth.verification_mail', $data, function ($message) use (&$recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name) {
            $message->to($recipient_mail, $recipient_name)->subject
                ($subject);
            $message->from($sender_mail, $sender_name);
        });
    }
    /**
     * END Send Verification Email
     *
     */

    public function httpRequest($url)
    {
        $pattern = "/http...([0-9a-zA-Z-.]*).([0-9]*).(.*)/";
        preg_match($pattern, $url, $args);
        $in = "";
        $fp = fsockopen($args[1], 80, $errno, $errstr, 30);
        if (!$fp) {
            return ("$errstr ($errno)");
        } else {
            $args[3] = "C" . $args[3];
            $out     = "GET /$args[3] HTTP/1.1\r\n";
            $out .= "Host: $args[1]:$args[2]\r\n";
            $out .= "User-agent: PARSHWA WEB SOLUTIONS\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);
            while (!feof($fp)) {
                $in .= fgets($fp, 128);
            }
        }
        fclose($fp);
        return ($in);
    }

    /**
     * Send SMS
     * @param phone (number), msg (string), user (string), password (string), sender_id (string), sms_url (url)
     *
     */
    public function SMSSend($phone, $msg, $debug = false, $user, $password, $senderid, $smsurl)
    {
        $url = 'username=' . $user;
        $url .= '&password=' . $password;
        $url .= '&sender=' . $senderid;
        $url .= '&to=' . urlencode($phone);
        $url .= '&message=' . urlencode($msg);
        $url .= '&priority=1';
        $url .= '&dnd=1';
        $url .= '&unicode=0';

        $urltouse = $smsurl . $url;
        if ($debug) {echo "Request: <br>$urltouse<br><br>";}

        //Open the URL to send the message
        $response = $this->httpRequest($urltouse);
        if ($debug) {
            echo "Response: <br><pre>" .
            str_replace(array("<", ">"), array("&lt;", "&gt;"), $response) .
                "</pre><br>";}

        return ($response);
    }
    /**
     * END Send SMS
     *
     */
}
