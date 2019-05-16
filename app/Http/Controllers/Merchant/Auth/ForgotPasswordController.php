<?php

namespace App\Http\Controllers\Merchant\Auth;

use App\Http\Controllers\Controller;
use App\Merchant;
use DB;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Mail;
use Carbon\Carbon;

class ForgotPasswordController extends RegisterController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */

    use SendsPasswordResetEmails;
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventForgotPassword = 'Forgot Password';
	protected $eventUpdate = 'Update';
	protected $eventLogout = 'Logout';

	/**
     * Log Description
     *
     * @var string
     */
	protected $forgotByEmail = 'User input email to request forgot password';
	protected $verificationEmailSuccess = 'User verified by email successfully';
	protected $verificationContactNoSuccess = 'User verified by contact no successfully';
	protected $verificationOtpSuccess = 'User verified by OTP successfully';
	protected $resendEmailVerification = 'User request to resend new email verification';
	protected $resendOTP = 'User request to resend new OTP';
	protected $createPasswordSuccess = 'User create new password successfully';

    protected $redirectTo = '/verification';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest:merchant');
    }

    /**
     * Load Forgot Password Page
     * if(user regitration is not complete, force user to complete the registration)
     */
    public function forgotPassword(Request $request)
    {
        return view('auth.forgot_password_email');
    }
    /**
     * END Load Forgot Password Page
     * if(user regitration is not complete, force user to complete the registration)
     */

    /**
     * Send Verification Code to email
     *
     */
    public function sendLinkForgot(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
			'captcha' => 'required|captcha',
        ],
			['captcha.captcha' => 'Wrong Captcha, please try again'
		]);

        $users = Merchant::where('email', '=', $request->email)->get();

        if (count($users) == 0) {
            return view('auth.forgot_password_email')->withErrors(new \Illuminate\Support\MessageBag(['email' => 'Email not found on the system.']));
        } else {
            Merchant::where('email', '=', $request->email)->update(['verification_code' => $this->checkUniqueVerificationCode()]);

            $user = Merchant::where('email', '=', $request->email)->first();
            $this->send_verification_email($user->name, $request->email, $user->name, '(Forgot Password) Email Verification from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), url('/verification/forgot_password/' . $user->verification_code), 'Here is the link to verify your account:');

			//ACTIVITY LOG
			activity($this->eventForgotPassword)->causedBy($user)->withProperties(['email' => $user->email])->log($this->forgotByEmail);
			//END ACTIVITY LOG

            return redirect($this->redirectPath())->with('email', $request->email);
        }
    }

    /**
     * Do Verification Code
     *
     */
    public function verificationEmailForgot($verification_code, Request $request)
    {
        $users = Merchant::where('verification_code', '=', $verification_code)
            ->where('updated_at', '>', Carbon::now()->subHours(24))
            ->get();

        if (count($users) == 0) {
            return view('auth.forgot_password_contact_no');
        } else {
            foreach ($users as $user) {
				//ACTIVITY LOG
				activity($this->eventForgotPassword)->causedBy($user)->withProperties(['verification_code' => $user->verification_code])->log($this->verificationEmailSuccess);
				//END ACTIVITY LOG

                return view('auth.forgot_password_contact_no', compact('user'));
            }
        }
    }

    /**
     * Do Verification Contact Number
     *
     */
    public function verificationContactNoForgot(Request $request)
    {
        $this->validate($request, [
            'contact_no' => 'required|min:4|max:4',
        ]);

        $users = Merchant::where('verification_code', '=', $request->verification_code)
            ->where('contact_no', 'like', '%' . $request->contact_no)
            ->get();

        if (count($users) == 0) {
            return redirect('/verification/forgot_password/' . $request->verification_code)->withErrors(new \Illuminate\Support\MessageBag(['contact_no' => 'Contact number not found on the server.']));
        } else {
            foreach ($users as $user) {
				//ACTIVITY LOG
				activity($this->eventForgotPassword)->causedBy($user)->withProperties(['verification_code' => $user->verification_code, 'contact_no' => $user->contact_no])->log($this->verificationContactNoSuccess);
				//END ACTIVITY LOG

                $user->otp = $this->checkUniqueOtp();
                $user->save();

                if($user->allow_login == '1' && $user->request_otp_times < 3)
                {
                  $user->otp = $this->checkUniqueOtp();
                  $user->request_otp_times = $user->request_otp_times + 1;
                  $user->last_send_otp = Carbon::now();
                  $user->save();

                  //Send OTP
                  $this->SMSSend($user->contact_no, 'Your OTP number is: ' . $user->otp, false, getLiveEnv('SMS_USERNAME'), getLiveEnv('SMS_PASSWORD'), getLiveEnv('SMS_SENDER'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
                  //End send OTP
                }
                else
                {
                  $user->allow_login = 0;
                  $user->save();
                }

                return redirect('/verification/forgot_password/otp')->with('verification_code', $user->verification_code);
            }
        }
    }

    /**
     * Do Verification OTP
     *
     */
    public function verificationOtpForgot(Request $request)
    {
        $rules = array(
            'otp' => 'required|min:6|max:6',
        );

        $input = Input::all();

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return redirect('verification/forgot_password/otp')->with('verification_code', $request->verification_code)->withErrors($validation);
        }

        $users = Merchant::where('verification_code', '=', $request->verification_code)
            ->where('otp', '=', $request->otp)
            ->where('updated_at', '>', Carbon::now()->subMinutes(5))
            ->get();

        if (count($users) == 0) {
            return redirect('/verification/forgot_password/otp')->with('verification_code', $request->verification_code)->withErrors(new \Illuminate\Support\MessageBag(['otp' => 'OTP number is invalid.']));
        } else {
            foreach ($users as $user) {
        				//ACTIVITY LOG
        				activity($this->eventForgotPassword)->causedBy($user)->withProperties(['verification_code' => $user->verification_code, 'otp' => $user->otp])->log($this->verificationOtpSuccess);
        				//END ACTIVITY LOG

        				$user->otp = $this->checkUniqueOtp();
        				$user->save();

                return redirect('/verification/forgot_password/change_password_forgot')->with('verification_code', $request->verification_code);
            }
        }
    }

    /**
     * Resend OTP
     *
     */
    public function resendOtpForgot(Request $request)
    {
        $user      = Merchant::where('verification_code', '=', $request->verification_code)->first();
        if($user->allow_login == '1' && $user->request_otp_times < 3)
        {
          $user->otp = $this->checkUniqueOtp();
          $user->request_otp_times = $user->request_otp_times + 1;
          $user->last_send_otp = Carbon::now();
          $user->save();

          //Send OTP
          $this->SMSSend($user->contact_no, 'Your OTP number is: ' . $user->otp, false, getLiveEnv('SMS_USERNAME'), getLiveEnv('SMS_PASSWORD'), getLiveEnv('SMS_SENDER'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
          //End send OTP

      		//ACTIVITY LOG
      		activity($this->eventForgotPassword)->causedBy($user)->withProperties(['verification_code' => $user->verification_code, 'otp' => $user->otp])->log($this->resendOTP);
      		//END ACTIVITY LOG
        }
        else
        {
          $user->allow_login = 0;
          $user->save();
        }

        return redirect('/verification/forgot_password/otp')->with('verification_code', $user->verification_code);
    }

    /**
     * Change Password
     *
     */
    public function changePasswordForgot(Request $request)
    {
        $user = Merchant::where('verification_code', '=', $request->verification_code)->first();

        $rules = array(
            'new_password'     => 'required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/|last_pass:' . ($user->id . '-merchant'),
            'confirm_password' => 'required|same:new_password|min:8|max:16',
        );

        $input = Input::all();

      $validation = Validator::make($input, $rules, ['new_password.last_pass' => 'New password must not be the same with your recent 5 previous passwords.']);

        if ($validation->fails()) {
            return redirect('/verification/forgot_password/change_password_forgot')->with('verification_code', $request->verification_code)->withErrors($validation);
        }

		$user->password = bcrypt($request->new_password);
		$user->allow_login = '1';
		$user->request_otp_times = '0';
		$user->is_active = '1';
		$user->save();

		//ACTIVITY LOG
		activity($this->eventForgotPassword)->causedBy($user)->withProperties(['email' => $user->email, 'verification_code' => $user->verification_code])->log($this->createPasswordSuccess);
		//END ACTIVITY LOG

        return redirect(route('merchants.change_password_complete'));
    }

    /**
     * Load Change Password Complete Page
     *
     */
     public function changePasswordComplete(Request $request)
     {
       return view('auth.change_password_forgot_complete');
     }
     /**
      * END Load Change Password Complete Page
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
         $users = Merchant::where('otp', '=', $otp)->get();
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
         $users = Merchant::where('verification_code', '=', $verification_code)->get();
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

    public function broker()
    {
        return Password::broker('merchant');
    }
}
