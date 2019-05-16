<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Merchant\Auth\RegisterController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;
use DB;
use Response;
use App\OtpGenerator;
use App\Merchant;
use App\MerchantPersonalInformation;
use App\MerchantBusinessDetail;
use App\MerchantContactDetail;
use App\MerchantBankDetail;
use App\MerchantWebsiteDetail;
use App\MerchantDocument;
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
	protected $updateBankDetailsSuccess = 'User bank details updated successfuly';
	protected $updateBusinessInformationSuccess = 'User business information updated successfuly';
	protected $updatePersonalInformationSuccess = 'User personal information updated successfuly';
	protected $updateAvatarSuccess = 'User avatar updated successfuly';
	protected $resendOtp = 'User request to resend new OTP';
	protected $verificationOtpSuccess = 'User verified by OTP successfully';

	protected $file_path = 'file/merchant_document/';

	public function __construct()
    {
        $this->middleware('auth:merchant');
    }

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

	public function edit()
    {
		$user = Auth::guard('merchant')->user();

		if($user->type == 'merchant')
		{
			$user = $user;
		}
		else
		{
			$user = Merchant::where('id', '=', $user->merchant_id)->first();
		}

		/*
		$merchantPersonalInfos = MerchantPersonalInformation::where('merchant_id', '=', $user->id)->get();
		return view('merchant.backend.pages.profile.edit', compact('user', 'userProfile', 'merchantPersonalInfos')); */

		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $user->id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $user->id)->first();
		$merchantBankDetail = MerchantBankDetail::where('merchant_id', $user->id)->first();
		$merchantWebsiteDetail = MerchantWebsiteDetail::where('merchant_id', $user->id)->first();
		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();

		$filePath = $this->file_path . $user->id . '/';

		return view('merchant.backend.pages.profile.edit_new', compact('user', 'merchantBusinessDetail', 'merchantContactDetail', 'merchantBankDetail', 'merchantWebsiteDetail', 'merchantDocument', 'filePath'));
    }

	public function updateAvatar(Request $request)
    {
		$imageFileName = $this->saveImage($request->avatar);

		$user = Auth::guard('merchant')->user();

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

		if($user->avatar_file_name != null)
		@unlink(public_path() . '/images/vendor/p' . $user->avatar_file_name);

		$user->avatar_file_name = 'p' . $imageFileName;
        $user->update();
        return 'AVATAR_UPDATED';
    }

	public function update(Request $request)
    {
		$user = Auth::guard('merchant')->user();

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
				'new_password'     => 'min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/|last_pass:' . ($user->id . '-merchant')
			],  ['new_password.last_pass' => 'New password must not be the same with your recent 5 previous passwords.']);
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

	public function updateBankDetails(Request $request)
	{
		if(Auth::guard('merchant')->user()->type == 'merchant')
			$user = Auth::guard('merchant')->user();
		else
			$user = Merchant::where('id', '=', Auth::guard('merchant')->user()->merchant_id)->first();

		$this->validate($request, [
				'bank_name'      => 'required',
				'account_number'      => 'required',
				'bank_ifsc_code'      => 'required'
			]);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'bank_name' => $request->bank_name,
																			'account_number' => $request->account_number,
																			'bank_ifsc_code' => $request->bank_ifsc_code,

																		],
																		'old' =>
																		[
																			'bank_name' => $user->bank_name,
																			'account_number' => $user->account_number,
																			'bank_ifsc_code' => $user->bank_ifsc_code,
																		],
																	])->log($this->updateBankDetailsSuccess);
		//END ACTIVITY LOG

		$user->bank_name = $request->bank_name;
		$user->account_number = $request->account_number;
		$user->bank_ifsc_code = $request->bank_ifsc_code;
		$user->save();

		return redirect('/profile')->with(
                'message',
                'Bank details was updated.'
            );
	}

	public function sendOTP(Request $request)
	{
		if(Auth::guard('merchant')->user()->type == 'merchant')
			$user = Auth::guard('merchant')->user();
		else
			$user = Merchant::where('id', '=', Auth::guard('merchant')->user()->merchant_id)->first();

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

	public function verifyOTP(Request $request)
	{
		if(Auth::guard('merchant')->user()->type == 'merchant')
			$user = Auth::guard('merchant')->user();
		else
			$user = Merchant::where('id', '=', Auth::guard('merchant')->user()->merchant_id)->first();

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

	function updateBusinessInformation(Request $request)
	{
		if(Auth::guard('merchant')->user()->type == 'merchant')
			$user = Auth::guard('merchant')->user();
		else
			$user = Merchant::where('id', '=', Auth::guard('merchant')->user()->merchant_id)->first();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'firm_name' => $request->firm_name,
																			'firm_address' => $request->firm_address,
																			'city' => $request->city,
																			'state' => $request->state,
																			'country' => $request->country,
																			'business_contact_no' => $request->business_contact_no,

																		],
																		'old' =>
																		[
																			'firm_name' => $user->firm_name,
																			'firm_address' => $user->firm_address,
																			'city' => $user->city,
																			'state' => $user->state,
																			'country' => $user->country,
																			'business_contact_no' => $user->business_contact_no,
																		],
																	])->log($this->updateBusinessInformationSuccess);
		//END ACTIVITY LOG

		$user->firm_name = $request->firm_name;
		$user->firm_address = $request->firm_address;
		$user->city = $request->city;
		$user->state = $request->state;
		$user->country = $request->country;
		$user->business_contact_no = $request->business_contact_no;

		if($request->vat_doc_check == 1)
		{
			if($request->hasFile('vat_doc_file'))
			{
				@unlink(public_path() . '/images/merchant/document/' . $user->vat_doc_file_name);

				$vatDocFile = $request->file('vat_doc_file');
				$vatDocFileName = str_random(10).'.'.$vatDocFile->getClientOriginalExtension();
				$vatDocFilePath = public_path() . '/images/merchant/document/' . $vatDocFileName;
				$vatDocFile->move(public_path() . '/images/merchant/document/', $vatDocFilePath);

				$user->vat_doc_file_name = $vatDocFileName;
				$user->vat_doc_path = $vatDocFilePath;
				$user->vat_doc_is_verified = '0';
				$user->vat_doc_verified_by_admin_id = null;
			}
		}
		else if($request->vat_doc_check == 0)
		{
			@unlink(public_path() . '/images/merchant/document/' . $user->vat_doc_file_name);
			$user->vat_doc_file_name = null;
			$user->vat_doc_path = null;
			$user->vat_doc_is_verified = '0';
			$user->vat_doc_verified_by_admin_id = null;
		}

		if($request->cst_doc_check == 1)
		{
			if($request->hasFile('cst_doc_file'))
			{
				@unlink(public_path() . '/images/merchant/document/' . $user->cst_doc_file_name);

				$cstDocFile = $request->file('cst_doc_file');
				$cstDocFileName = str_random(10).'.'.$cstDocFile->getClientOriginalExtension();
				$cstDocFilePath = public_path() . '/images/merchant/document/' . $cstDocFileName;
				$cstDocFile->move(public_path() . '/images/merchant/document/', $cstDocFilePath);

				$user->cst_doc_file_name = $cstDocFileName;
				$user->cst_doc_path = $cstDocFilePath;
				$user->cst_doc_is_verified = '0';
				$user->cst_doc_verified_by_admin_id = null;
			}
		}
		else if($request->cst_doc_check == 0)
		{
			@unlink(public_path() . '/images/merchant/document/' . $user->cst_doc_file_name);
			$user->cst_doc_file_name = null;
			$user->cst_doc_path = null;
			$user->cst_doc_is_verified = '0';
			$user->cst_doc_verified_by_admin_id = null;
		}

		if($request->service_tax_doc_check == 1)
		{
			if($request->hasFile('service_tax_doc_file'))
			{
				@unlink(public_path() . '/images/merchant/document/' . $user->service_tax_doc_file_name);

				$service_taxDocFile = $request->file('service_tax_doc_file');
				$service_taxDocFileName = str_random(10).'.'.$service_taxDocFile->getClientOriginalExtension();
				$service_taxDocFilePath = public_path() . '/images/merchant/document/' . $service_taxDocFileName;
				$service_taxDocFile->move(public_path() . '/images/merchant/document/', $service_taxDocFilePath);

				$user->service_tax_doc_file_name = $service_taxDocFileName;
				$user->service_tax_doc_path = $service_taxDocFilePath;
				$user->service_tax_doc_is_verified = '0';
				$user->service_tax_doc_verified_by_admin_id = null;
			}
		}
		else if($request->service_tax_doc_check == 0)
		{
			@unlink(public_path() . '/images/merchant/document/' . $user->service_tax_doc_file_name);
			$user->service_tax_doc_file_name = null;
			$user->service_tax_doc_path = null;
			$user->service_tax_doc_is_verified = '0';
			$user->service_tax_doc_verified_by_admin_id = null;
		}

		if($request->hasFile('gumasta_doc_file'))
		{
			@unlink(public_path() . '/images/merchant/document/' . $user->gumasta_doc_file_name);

			$gumastaDocFile = $request->file('gumasta_doc_file');
			$gumastaDocFileName = str_random(10).'.'.$gumastaDocFile->getClientOriginalExtension();
			$gumastaDocFilePath = public_path() . '/images/merchant/document/' . $gumastaDocFileName;
			$gumastaDocFile->move(public_path() . '/images/merchant/document/', $gumastaDocFilePath);

			$user->gumasta_doc_file_name = $gumastaDocFileName;
			$user->gumasta_doc_path = $gumastaDocFilePath;
			$user->gumasta_doc_is_verified = '0';
			$user->gumasta_doc_verified_by_admin_id = null;
		}



		$user->save();



		return redirect('/profile')->with(
                'message',
                'Business Information was updated.'
            );
	}

	function updatePersonalInformation(Request $request)
	{
		if(Auth::guard('merchant')->user()->type == 'merchant')
			$user = Auth::guard('merchant')->user();
		else
			$user = Merchant::where('id', '=', Auth::guard('merchant')->user()->merchant_id)->first();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->log($this->updatePersonalInformationSuccess);
		//END ACTIVITY LOG

		$merchantPersonalInformations = MerchantPersonalInformation::where('merchant_id', '=', $user->id)->get();
		if(count($merchantPersonalInformations) > 0)
		{
			$totalPerson = $request->total_person;
			$z = 0;
			$exist = 0;
			$finishLoop = false;
			foreach($merchantPersonalInformations as $merchantPersonalInformation)
			{
				$z++; //ex: 3
				for($y = $z; $y <= $totalPerson; $y++)
				{
					if($request->input('owner_name_' . $y) != null)
					{
						$exist++; //ex: 2

						$merchantPersonalInformation->merchant_id = $user->id;
						$merchantPersonalInformation->owner_name = $request->input('owner_name_' . $y);
						$merchantPersonalInformation->personal_address = $request->input('personal_address_' . $y);
						$merchantPersonalInformation->personal_contact_no = $request->input('personal_contact_no_' . $y);
						$merchantPersonalInformation->city = $request->input('personal_information_city_' . $y);
						$merchantPersonalInformation->state = $request->input('personal_information_state_' . $y);
						$merchantPersonalInformation->country = $request->input('personal_information_country_' . $y);

						$merchantPersonalInformation->personal_pan_card = $request->input('personal_pan_card_' . $y);
						if(is_uploaded_file($_FILES['personal_pan_card_file_' . $y]['name']))
						{
							if($_FILES['personal_pan_card_file_' . $y]['size'] > 0 && $_FILES['personal_pan_card_file_' . $y]['error'] == 0)
							{
								@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->personal_pan_card_filename);

								$docFile = $request->file('personal_pan_card_file_' . $y);
								$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
								$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
								$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

								$merchantPersonalInformation->personal_pan_card_filename = $docFileName;
								$merchantPersonalInformation->personal_pan_card_path = $docFilePath;
								$merchantPersonalInformation->personal_pan_card_is_verified = '0';
								$merchantPersonalInformation->personal_pan_card_verified_by_admin_id = null;
							}
						}

						if($request->input('aadhar_no_' . $y) != null)
						{
							$merchantPersonalInformation->aadhar_no = $request->input('aadhar_no_' . $y);
							if($_FILES['aadhar_no_file_' . $y]['size'] > 0 && $_FILES['aadhar_no_file_' . $y]['error'] == 0)
							{
								@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->aadhar_filename);

								$docFile = $request->file('aadhar_no_file_' . $y);
								$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
								$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
								$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

								$merchantPersonalInformation->aadhar_filename = $docFileName;
								$merchantPersonalInformation->aadhar_path = $docFilePath;
								$merchantPersonalInformation->aadhar_is_verified = '0';
								$merchantPersonalInformation->aadhar_verified_by_admin_id = null;
							}
						}

						if($request->input('passport_no_' . $y) != null)
						{
							$merchantPersonalInformation->passport_no = $request->input('passport_no_' . $y);
							if($_FILES['passport_no_file_' . $y]['size'] > 0 && $_FILES['passport_no_file_' . $y]['error'] == 0)
							{
								@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->passport_filename);

								$docFile = $request->file('passport_no_file_' . $y);
								$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
								$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
								$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

								$merchantPersonalInformation->passport_filename = $docFileName;
								$merchantPersonalInformation->passport_path = $docFilePath;
								$merchantPersonalInformation->passport_is_verified = '0';
								$merchantPersonalInformation->passport_verified_by_admin_id = null;
							}
						}

						if($_FILES['voter_id_card_file_' . $y]['size'] > 0 && $_FILES['voter_id_card_file_' . $y]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->voter_id_card_filename);

							$docFile = $request->file('voter_id_card_file_' . $y);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->voter_id_card_filename = $docFileName;
							$merchantPersonalInformation->voter_id_card_path = $docFilePath;
							$merchantPersonalInformation->voter_id_card_is_verified = '0';
							$merchantPersonalInformation->voter_id_card_verified_by_admin_id = null;
						}

						if($_FILES['electricity_bill_file_' . $y]['size'] > 0 && $_FILES['electricity_bill_file_' . $y]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->electricity_bill_filename);

							$docFile = $request->file('electricity_bill_file_' . $y);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->electricity_bill_filename = $docFileName;
							$merchantPersonalInformation->electricity_bill_path = $docFilePath;
							$merchantPersonalInformation->electricity_bill_is_verified = '0';
							$merchantPersonalInformation->electricity_bill_verified_by_admin_id = null;
						}

						if($_FILES['landline_bill_file_' . $y]['size'] > 0 && $_FILES['landline_bill_file_' . $y]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->landline_bill_filename);

							$docFile = $request->file('landline_bill_file_' . $y);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->landline_bill_filename = $docFileName;
							$merchantPersonalInformation->landline_bill_path = $docFilePath;
							$merchantPersonalInformation->landline_bill_is_verified = '0';
							$merchantPersonalInformation->landline_bill_verified_by_admin_id = null;
						}

						if($_FILES['bank_account_statement_file_' . $y]['size'] > 0 && $_FILES['bank_account_statement_file_' . $y]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->bank_account_statement_filename);

							$docFile = $request->file('bank_account_statement_file_' . $y);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->bank_account_statement_filename = $docFileName;
							$merchantPersonalInformation->bank_account_statement_path = $docFilePath;
							$merchantPersonalInformation->bank_account_statement_is_verified = '0';
							$merchantPersonalInformation->bank_account_statement_verified_by_admin_id = null;
						}

						$merchantPersonalInformation->save();
						break;
					}
					if($y == $totalPerson) $finishLoop = true;
				}

				if($finishLoop)
				{
					if($z > $exist) $merchantPersonalInformation->delete();
				}
			}

			if($z < $totalPerson)
			{
				$z++;
				for($x = $z; $x <= $totalPerson; $x++)
				{
					if($request->input('owner_name_' . $x) != null)
					{
						$merchantPersonalInformation = new MerchantPersonalInformation();
						$merchantPersonalInformation->merchant_id = $user->id;
						$merchantPersonalInformation->owner_name = $request->input('owner_name_' . $x);
						$merchantPersonalInformation->personal_address = $request->input('personal_address_' . $x);
						$merchantPersonalInformation->personal_contact_no = $request->input('personal_contact_no_' . $x);
						$merchantPersonalInformation->city = $request->input('personal_information_city_' . $x);
						$merchantPersonalInformation->state = $request->input('personal_information_state_' . $x);
						$merchantPersonalInformation->country = $request->input('personal_information_country_' . $x);

						$merchantPersonalInformation->personal_pan_card = $request->input('personal_pan_card_' . $x);
						if($_FILES['personal_pan_card_file_' . $x]['size'] > 0 && $_FILES['personal_pan_card_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->personal_pan_card_filename);

							$docFile = $request->file('personal_pan_card_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->personal_pan_card_filename = $docFileName;
							$merchantPersonalInformation->personal_pan_card_path = $docFilePath;
							$merchantPersonalInformation->personal_pan_card_is_verified = '0';
							$merchantPersonalInformation->personal_pan_card_verified_by_admin_id = null;
						}

						if($request->input('aadhar_no_' . $x) != null)
						{
							$merchantPersonalInformation->aadhar_no = $request->input('aadhar_no_' . $x);
							if($_FILES['aadhar_no_file_' . $x]['size'] > 0 && $_FILES['aadhar_no_file_' . $x]['error'] == 0)
							{
								@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->aadhar_filename);

								$docFile = $request->file('aadhar_no_file_' . $x);
								$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
								$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
								$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

								$merchantPersonalInformation->aadhar_filename = $docFileName;
								$merchantPersonalInformation->aadhar_path = $docFilePath;
								$merchantPersonalInformation->aadhar_is_verified = '0';
								$merchantPersonalInformation->aadhar_verified_by_admin_id = null;
							}
						}

						if($request->input('passport_no_' . $x) != null)
						{
							$merchantPersonalInformation->passport_no = $request->input('passport_no_' . $x);
							if($_FILES['passport_no_file_' . $x]['size'] > 0 && $_FILES['passport_no_file_' . $x]['error'] == 0)
							{
								@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->passport_filename);

								$docFile = $request->file('passport_no_file_' . $x);
								$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
								$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
								$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

								$merchantPersonalInformation->passport_filename = $docFileName;
								$merchantPersonalInformation->passport_path = $docFilePath;
								$merchantPersonalInformation->passport_is_verified = '0';
								$merchantPersonalInformation->passport_verified_by_admin_id = null;
							}
						}

						if($_FILES['voter_id_card_file_' . $x]['size'] > 0 && $_FILES['voter_id_card_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->voter_id_card_filename);

							$docFile = $request->file('voter_id_card_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->voter_id_card_filename = $docFileName;
							$merchantPersonalInformation->voter_id_card_path = $docFilePath;
							$merchantPersonalInformation->voter_id_card_is_verified = '0';
							$merchantPersonalInformation->voter_id_card_verified_by_admin_id = null;
						}

						if($_FILES['electricity_bill_file_' . $x]['size'] > 0 && $_FILES['electricity_bill_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->electricity_bill_filename);

							$docFile = $request->file('electricity_bill_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->electricity_bill_filename = $docFileName;
							$merchantPersonalInformation->electricity_bill_path = $docFilePath;
							$merchantPersonalInformation->electricity_bill_is_verified = '0';
							$merchantPersonalInformation->electricity_bill_verified_by_admin_id = null;
						}

						if($_FILES['landline_bill_file_' . $x]['size'] > 0 && $_FILES['landline_bill_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->landline_bill_filename);

							$docFile = $request->file('landline_bill_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->landline_bill_filename = $docFileName;
							$merchantPersonalInformation->landline_bill_path = $docFilePath;
							$merchantPersonalInformation->landline_bill_is_verified = '0';
							$merchantPersonalInformation->landline_bill_verified_by_admin_id = null;
						}

						if($_FILES['bank_account_statement_file_' . $x]['size'] > 0 && $_FILES['bank_account_statement_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->bank_account_statement_filename);

							$docFile = $request->file('bank_account_statement_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->bank_account_statement_filename = $docFileName;
							$merchantPersonalInformation->bank_account_statement_path = $docFilePath;
							$merchantPersonalInformation->bank_account_statement_is_verified = '0';
							$merchantPersonalInformation->bank_account_statement_verified_by_admin_id = null;
						}

						$merchantPersonalInformation->save();
					}
				}
			  }
			}
		else
		{
			for($x = 1; $x <= $request->total_person; $x++)
			{

				if($request->input('owner_name_' . $x) != null)
				{
					$merchantPersonalInformation = new MerchantPersonalInformation();
					$merchantPersonalInformation->merchant_id = $user->id;
					$merchantPersonalInformation->owner_name = $request->input('owner_name_' . $x);
					$merchantPersonalInformation->personal_address = $request->input('personal_address_' . $x);
					$merchantPersonalInformation->personal_contact_no = $request->input('personal_contact_no_' . $x);
					$merchantPersonalInformation->city = $request->input('personal_information_city_' . $x);
					$merchantPersonalInformation->state = $request->input('personal_information_state_' . $x);
					$merchantPersonalInformation->country = $request->input('personal_information_country_' . $x);

					$merchantPersonalInformation->personal_pan_card = $request->input('personal_pan_card_' . $x);
					if($_FILES['personal_pan_card_file_' . $x]['size'] > 0 && $_FILES['personal_pan_card_file_' . $x]['error'] == 0)
					{
						@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->personal_pan_card_filename);

						$docFile = $request->file('personal_pan_card_file_' . $x);
						$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
						$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
						$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

						$merchantPersonalInformation->personal_pan_card_filename = $docFileName;
						$merchantPersonalInformation->personal_pan_card_path = $docFilePath;
						$merchantPersonalInformation->personal_pan_card_is_verified = '0';
						$merchantPersonalInformation->personal_pan_card_verified_by_admin_id = null;
					}

					if($request->input('aadhar_no_' . $x) != null)
					{
						$merchantPersonalInformation->aadhar_no = $request->input('aadhar_no_' . $x);
						if($_FILES['aadhar_no_file_' . $x]['size'] > 0 && $_FILES['aadhar_no_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->aadhar_filename);

							$docFile = $request->file('aadhar_no_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->aadhar_filename = $docFileName;
							$merchantPersonalInformation->aadhar_path = $docFilePath;
							$merchantPersonalInformation->aadhar_is_verified = '0';
							$merchantPersonalInformation->aadhar_verified_by_admin_id = null;
						}
					}

					if($request->input('passport_no_' . $x) != null)
					{
						$merchantPersonalInformation->passport_no = $request->input('passport_no_' . $x);
						if($_FILES['passport_no_file_' . $x]['size'] > 0 && $_FILES['passport_no_file_' . $x]['error'] == 0)
						{
							@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->passport_filename);

							$docFile = $request->file('passport_no_file_' . $x);
							$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
							$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
							$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

							$merchantPersonalInformation->passport_filename = $docFileName;
							$merchantPersonalInformation->passport_path = $docFilePath;
							$merchantPersonalInformation->passport_is_verified = '0';
							$merchantPersonalInformation->passport_verified_by_admin_id = null;
						}
					}

					if($_FILES['voter_id_card_file_' . $x]['size'] > 0 && $_FILES['voter_id_card_file_' . $x]['error'] == 0)
					{
						@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->voter_id_card_filename);

						$docFile = $request->file('voter_id_card_file_' . $x);
						$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
						$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
						$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

						$merchantPersonalInformation->voter_id_card_filename = $docFileName;
						$merchantPersonalInformation->voter_id_card_path = $docFilePath;
						$merchantPersonalInformation->voter_id_card_is_verified = '0';
						$merchantPersonalInformation->voter_id_card_verified_by_admin_id = null;
					}

					if($_FILES['electricity_bill_file_' . $x]['size'] > 0 && $_FILES['electricity_bill_file_' . $x]['error'] == 0)
					{
						@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->electricity_bill_filename);

						$docFile = $request->file('electricity_bill_file_' . $x);
						$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
						$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
						$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

						$merchantPersonalInformation->electricity_bill_filename = $docFileName;
						$merchantPersonalInformation->electricity_bill_path = $docFilePath;
						$merchantPersonalInformation->electricity_bill_is_verified = '0';
						$merchantPersonalInformation->electricity_bill_verified_by_admin_id = null;
					}

					if($_FILES['landline_bill_file_' . $x]['size'] > 0 && $_FILES['landline_bill_file_' . $x]['error'] == 0)
					{
						@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->landline_bill_filename);

						$docFile = $request->file('landline_bill_file_' . $x);
						$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
						$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
						$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

						$merchantPersonalInformation->landline_bill_filename = $docFileName;
						$merchantPersonalInformation->landline_bill_path = $docFilePath;
						$merchantPersonalInformation->landline_bill_is_verified = '0';
						$merchantPersonalInformation->landline_bill_verified_by_admin_id = null;
					}

					if($_FILES['bank_account_statement_file_' . $x]['size'] > 0 && $_FILES['bank_account_statement_file_' . $x]['error'] == 0)
					{
						@unlink(public_path() . '/images/merchant/document/' . $merchantPersonalInformation->bank_account_statement_filename);

						$docFile = $request->file('bank_account_statement_file_' . $x);
						$docFileName = str_random(10).'.'.$docFile->getClientOriginalExtension();
						$docFilePath = public_path() . '/images/merchant/document/' . $docFileName;
						$docFile->move(public_path() . '/images/merchant/document/', $docFilePath);

						$merchantPersonalInformation->bank_account_statement_filename = $docFileName;
						$merchantPersonalInformation->bank_account_statement_path = $docFilePath;
						$merchantPersonalInformation->bank_account_statement_is_verified = '0';
						$merchantPersonalInformation->bank_account_statement_verified_by_admin_id = null;
					}

					$merchantPersonalInformation->save();
				}
			}
		}

		return redirect('/profile')->with(
                'message',
                'Personal Information was updated.'
            );
	}
}
