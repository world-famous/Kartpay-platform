<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Auth\RegisterController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;
use Response;
use App\OtpGenerator;
use Carbon\Carbon;

class ProfileController extends RegisterController
{
	use LogsActivity;


	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Profile';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'User updated successfuly';
	protected $updateAvatarSuccess = 'User avatar updated successfuly';

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	/**
     * Save Image
     *
     * @param: data image
		 * @return: string (filename)
     */
	public function saveImage($image)
	{
		$data = $image;

		list($type, $data) = explode(';', $data);
		list(, $data)      = explode(',', $data);
		$data = base64_decode($data);

		$newFilename = $this->random_str(10) . '.jpg';
		file_put_contents(public_path() . '/images/vendor/p' . $newFilename, $data);
		return $newFilename;
	}
	/**
     * END Save Image
     *
     * @param: data image
		 * @return: string (filename)
     */

	 /**
	 	 * Edit Profile
	 	 *
	 	 */
	public function edit()
  {
		$user = Auth::guard('admin')->user();
		return view('panel.backend.pages.profile.edit', compact('user'));
  }
	/**
		* END Edit Profile
		*
		*/

	/**
		* Update Merchant Avatar
		*/
	public function updateAvatar(Request $request)
  {
		$imageFileName = $this->saveImage($request->avatar);
		$user = Auth::guard('admin')->user();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'avatar_file_name' => $imageFileName,
																		],
																		'old' =>
																		[
																			'avatar_file_name' => $user->avatar_file_name,
																		],
																	])->log($this->updateAvatarSuccess);
		//END ACTIVITY LOG

		if($user->avatar_file_name != null)	@unlink(public_path() . '/images/vendor/p' . $user->avatar_file_name);

		$user->avatar_file_name = 'p' . $imageFileName;
        $user->save();
        return 'AVATAR_UPDATED';
  }
	/**
		* END Update Merchant Avatar
		*/

	/**
		* Update Merchant Avatar
		*/
	public function update(Request $request)
    {
		$user = Auth::guard('admin')->user();

		$oldEmail               = $user->email;
        $validationParam        = '';
        $rEmail                 = $request->email;

        if ($rEmail != $user->email) :
            $validationParam    = '|unique:users';
        endif;

		if($request->new_password != '')
		{
			$this->validate($request, [
				'first_name'      => 'required|max:100',
				'last_name'      => 'required|max:100',
				'country_code'      => 'required|min:3',
				'contact_no'      => 'required|max:10',
				'email'           => 'email|required|max:100'.$validationParam,
				'new_password'     => 'min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/|last_pass:' . ($user->id . '-admin')
			], ['new_password.last_pass' => "New password must not be the same with your recent 5 previous passwords."]);
		}
		else
		{
			$this->validate($request, [
				'first_name'      => 'required|max:100',
				'last_name'      => 'required|max:100',
				'country_code'      => 'required|min:3',
				'contact_no'      => 'required|max:10',
				'email'           => 'email|required|max:100'.$validationParam
			]);
		}

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'first_name' => $request->first_name,
																			'last_name' => $request->last_name,
																			'country_code' => $request->country_code,
																			'contact_no' => $request->contact_no,
																			'email' => $request->email,
																			'is_active' => $request->is_active,

																		],
																		'old' =>
																		[
																			'first_name' => $user->first_name,
																			'last_name' => $user->last_name,
																			'country_code' => $user->country_code,
																			'contact_no' => $user->contact_no,
																			'email' => $user->email,
																			'is_active' => $user->is_active,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->country_code = $request->country_code;
		$user->contact_no = $request->contact_no;
		$user->email = $request->email;
		if($request->new_password != '') $user->password = bcrypt($request->new_password);
		$user->is_active = $request->is_active;
		$user->save();

		return redirect('/profile')->with(
                'message',
                'Profile was updated.'
            );
	}
	/**
		* END Update Merchant Avatar
		*/

	/**
		* Send OTP
		*/
	public function sendOTP(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$otpGenerators = OtpGenerator::where('contact_no', '=', $request->contact_no)->get();
		if(count($otpGenerators) > 0)
		{
			foreach($otpGenerators as $otpGenerator)
			{
				$otpGenerator->otp = $this->random_otp(6);
				$otpGenerator->is_verified = '0';
				$otpGenerator->save();

				//Send OTP
				$this->SMSSend($otpGenerator->contact_no, 'Your OTP number is: ' . $otpGenerator->otp, false, getLiveEnv('SMS_USERNAME'), getLiveEnv('SMS_PASSWORD'), getLiveEnv('SMS_SENDER'), 'http://www.sms.kartpay.com/ComposeSMS.aspx?');
				//End send OTP

				if($request->type == 'send')
				{
					return Response::json(
						[
							'response' => 'success',
							'element_id' => $request->element_id,
							'type' => 'send'
						]
					);
				}
				elseif($request->type == 'resend')
				{
					//ACTIVITY LOG
					activity($this->eventUpdate)->causedBy($user)->withProperties(['contact_no' => $request->contact_no, 'type' => 'resend'])->log($this->resendOtp);
					//END ACTIVITY LOG
					return Response::json(
						[
							'response' => 'success',
							'element_id' => $request->element_id,
							'type' => 'resend'
						]
					);
				}
			}
		}
		else
		{
			$otpGenerator = new OtpGenerator();
			$otpGenerator->contact_no = $request->contact_no;
			$otpGenerator->otp = $this->random_otp(6);
			$otpGenerator->is_verified = '0';
			$otpGenerator->save();

			if($request->type == 'send')
			{
				return Response::json(
					[
						'response' => 'success',
						'element_id' => $request->element_id,
						'type' => 'send'
					]
				);
			}
			elseif($request->type == 'resend')
			{
				//ACTIVITY LOG
				activity($this->eventUpdate)->causedBy($user)->withProperties(['contact_no' => $request->contact_no, 'type' => 'resend'])->log($this->resendOtp);
				//END ACTIVITY LOG
				return Response::json(
					[
						'response' => 'success',
						'element_id' => $request->element_id,
						'type' => 'resend'
					]
				);
			}
		}
	}
	/**
		* END Send OTP
		*/

	/**
 	 * Verify OTP
 	 */
	public function verifyOTP(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$otpGenerators = OtpGenerator::where('contact_no', '=', $request->contact_no)
										->where('otp', '=', $request->otp)
										->where('updated_at', '>', Carbon::now()->subMinutes(5))
										->get();
		if(count($otpGenerators) > 0)
		{
			foreach($otpGenerators as $otpGenerator)
			{
				$otpGenerator->otp = $this->random_otp(6);
				$otpGenerator->is_verified = '1';
				$otpGenerator->save();

				//ACTIVITY LOG
				activity($this->eventUpdate)->causedBy($user)->withProperties(['contact_no' => $request->contact_no, 'otp' => $request->otp])->log($this->verificationOtpSuccess);
				//END ACTIVITY LOG

				return Response::json(
					[
						'response' => 'success',
						'element_id' => $request->element_id,
						'message' => 'Verification Success'
					]
				);
			}
		}
		else
		{
			return Response::json(
					[
						'response' => 'error',
						'element_id' => $request->element_id,
						'message' => 'OTP number is not valid. Click "Resend OTP" button if you want to request new OTP'
					]
				);
		}
	}
	/**
 	 * END Verify OTP
 	 */

 /**
  * Check valid OTP (unique)
  */
	public function checkValidOtp(Request $request)
	{
		$otpGenerators = OtpGenerator::where('contact_no', '=', $request->contact_no)->where('is_verified', '=', '1')->get();
		if(count($otpGenerators) == 0)
		{
			return Response::json(
				[
					'response' => 'error',
					'message' => 'Contact No is not verified yet. Please check it.'
				]
			);
		}
		else
		{
			return Response::json(
				[
					'response' => 'success',
					'message' => 'Contact No is verified.'
				]
			);
		}
	}
	/**
   * END Check valid OTP (unique)
   */

 /**
  * Generate Random String
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
   * END Generate Random String
   */
}
