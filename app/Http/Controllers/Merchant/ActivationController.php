<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use App\Merchant;
use App\MerchantBusinessDetail;
use App\MerchantContactDetail;
use App\MerchantDocument;
use App\MerchantBankDetail;
use App\Country;
use App\State;
use App\City;
use App\TermCondition;
use App\Couriers;
use App\Bank;
use App\MerchantDocumentCourier;
use App\MerchantWebsiteDetail;
use Auth;
use Response;

class ActivationController extends Controller
{
	protected $file_storage = 'app/merchant_document/';
	protected $file_path = 'file/merchant_document/';
	protected $eventUpdate = 'Activation';
	protected $eventUpdateMerchantDocument = 'Merchant Document';
	protected $updateActivationStepSuccess = 'User activation step updated successfuly';
	protected $addBusinessDetailsSuccess = 'User business details added successfuly';
	protected $addContactDetailsSuccess = 'User contact details added successfuly';
	protected $addBankDetailsSuccess = 'User bank details added successfuly';
	protected $addMerchantDocumentSuccess = 'User merchant document added successfuly';
	protected $addWebsiteDetailsSuccess = 'User website details added successfuly';
	protected $removeMerchantDocumentSuccess = 'User merchant document removed successfuly';

	public function __construct()
  {
      $this->middleware('auth:merchant');
  }

	/**
	 * Check Step for existing user on activation, user will redirect to next step after finished the last step
	 *
	 */
	public function checkStep(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		if($user->last_activation_step == 'step0') return redirect(route('merchants.activation.step1'));
		if($user->last_activation_step == 'step1') return redirect(route('merchants.activation.step2'));

		$uri = $request->path();
		if($uri == 'activation/step_2') return redirect(route('merchants.activation.step2'));
		if($uri == 'activation/step_3') return redirect(route('merchants.activation.step3'));
		if($uri == 'activation/step_4') return redirect(route('merchants.activation.step4'));

		return redirect(route('merchants.activation.step1', compact('user')));
	}
	/**
	 * END Check Step for existing user on activation, user will redirect to next step after finished the last step
	 *
	 */

	 /**
	  * Load Activation Step 1 Page
	  *
	  */
	public function step1()
	{
		$user = Auth::guard('merchant')->user();
		if($user->last_activation_step == 'step2') return redirect(route('merchants.activation.step3'));
		if($user->last_activation_step == 'step3') return redirect(route('merchants.activation.step4'));
		if($user->last_activation_step == 'step4') return redirect(route('merchants.activation.step4'));

		return view('merchant.backend.pages.activation.step1', compact('user'));
	}
	/**
	 * END Load Activation Step 1 Page
	 *
	 */

	 /**
	  * Load Activation Step 2 Page
	  *
	  */
	public function step2()
	{
		$user = Auth::guard('merchant')->user();
		if($user->last_activation_step == 'step0') return redirect(route('merchants.activation.step1'));

		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $user->id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $user->id)->first();
		$merchantBankDetail = MerchantBankDetail::where('merchant_id', $user->id)->first();
		$merchantWebsiteDetail = MerchantWebsiteDetail::where('merchant_id', $user->id)->first();
		$banks = bank::where('bank_status', 'Active')->get();

		$countrys = Country::all();
		if($merchantBusinessDetail)
		{
			$businessStates = State::where('country_id', $merchantBusinessDetail->business_country)->get();
			$businessCitys = City::where('state_id', $merchantBusinessDetail->business_state)->get();
		}
		else
		{
			$businessStates = State::where('country_id', '-1')->get();
			$businessCitys = City::where('state_id', '-1')->get();
		}
		if($merchantContactDetail)
		{
			$contactDetailStates = State::where('country_id', $merchantContactDetail->owner_country)->get();
			$contactDetailCitys = City::where('state_id', $merchantContactDetail->owner_state)->get();
		}
		else
		{
			$contactDetailStates = State::where('country_id', '-1')->get();
			$contactDetailCitys = City::where('state_id', '-1')->get();
		}

		$termCondition = TermCondition::find('1');
		return view('merchant.backend.pages.activation.step2', compact('user', 'merchantBusinessDetail', 'merchantContactDetail', 'merchantBankDetail', 'merchantWebsiteDetail', 'countrys', 'businessStates', 'contactDetailStates', 'businessCitys', 'contactDetailCitys', 'termCondition', 'banks'));
	}
	/**
	 * END Load Activation Step 2 Page
	 *
	 */

	 /**
	  * Load Activation Step 3 Page
	  *
	  */
	public function step3()
	{
		$user = Auth::guard('merchant')->user();
		if($user->last_activation_step == 'step0') return redirect(route('merchants.activation.step1'));
		if($user->last_activation_step == 'step1') return redirect(route('merchants.activation.step2'));

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();
		$filePath = $this->file_path . $user->id . '/';
		return view('merchant.backend.pages.activation.step3', compact('user', 'merchantDocument', 'filePath'));
	}
	/**
	 * END Load Activation Step 3 Page
	 *
	 */

	 /**
	  * Load Activation Step 4 Page
	  *
	  */
	public function step4()
	{
		$user = Auth::guard('merchant')->user();
		if($user->last_activation_step == 'step0') return redirect(route('merchants.activation.step1'));
		if($user->last_activation_step == 'step1') return redirect(route('merchants.activation.step2'));
		if($user->last_activation_step == 'step2') return redirect(route('merchants.activation.step3'));

		return view('merchant.backend.pages.activation.step4', compact('user'));
	}
	/**
	 * END Load Activation Step 4 Page
	 *
	 */

	 /**
	  * Load Not Approve Document Page
	  *
	  */
	public function notApproveDocument()
	{
		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();
		$filePath = $this->file_path . $user->id . '/';
		return view('merchant.backend.pages.activation.not_approve_document', compact('user', 'merchantDocument', 'filePath'));
	}
	/**
	 * END Load Not Approve Document Page
	 *
	 */

	 /**
	  * Display Activity Log Data
	  * @return array
	  */
	public function display()
	{
		$activitys = Activity::leftJoin('merchants', 'merchants.id', '=', 'activity_log.causer_id')
								->select('activity_log.*', 'merchants.first_name', 'merchants.last_name')
								->where('causer_id', '=', Auth::guard('merchant')->user()->id)
								->where('causer_type', '=', 'App\Merchant')
								->get();

		$newActivity = array();
		foreach($activitys as $activity)
		{
			$objActivity = array();
			$objActivity[] = $activity->log_name;
			$objActivity[] = $activity->description;
			$objActivity[] = $activity->first_name . ' ' . $activity->last_name;

			$objActivity[] = 'Merchant';
			$objActivity[] = date('M j Y g:i A', strtotime($activity->created_at));
			$newActivity[] = $objActivity;
		}
		return array( 'data' => $newActivity );
	}
	/**
	 * END Display Activity Log Data
	 * @return array
	 */

	 /**
	  * Do Process Activation Step 1
	  * @return redirect
	  */
	public function processStep1(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		Merchant::where('id', $user->id)->update(['last_activation_step' => 'step1']);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'last_activation_step' => 'step1',
																		],
																		'old' =>
																		[
																			'last_activation_step' => 'step0',
																		],
																	])->log($this->updateActivationStepSuccess);
		//END ACTIVITY LOG

		return redirect(route('merchants.activation.step2'));
	}
	/**
	 * END Do Process Activation Step 1
	 * @return redirect
	 */

	 /**
	  * Do Process Activation Step 2
	  * @return redirect
	  */
	public function processStep2(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $user->id)->first();
		if(!$merchantBusinessDetail)
		{
			$merchantBusinessDetail = new MerchantBusinessDetail();
			$merchantBusinessDetail->merchant_id = $user->id;
		}
		$merchantBusinessDetail->business_legal_name = $request->business_legal_name;
		$merchantBusinessDetail->business_trading_name = $request->business_trading_name;
		$merchantBusinessDetail->business_registered_address = $request->business_registered_address;
		$merchantBusinessDetail->business_state = $request->business_state;

		$city = City::where('city_name', '=', $request->business_city)->first();
		if(!$city)
		{
			$city = new City();
			$city->city_name = $request->business_city;
			$city->city_status = 'Active';
			$city->state_id = $request->business_state;
			$city->save();
		}
		$merchantBusinessDetail->business_city = $city->id;
		$merchantBusinessDetail->business_country = $request->business_country;
		$merchantBusinessDetail->business_pin_code = $request->business_pin_code;
		$merchantBusinessDetail->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'business_legal_name' => $request->business_legal_name,
																			'business_trading_name' => $request->business_trading_name,
																			'business_registered_address' => $request->business_registered_address,
																			'business_state' => $request->business_state,
																			'business_city' => $request->business_city,
																			'business_country' => $request->business_country,
																			'business_pin_code' => $request->business_pin_code,
																	])->log($this->addBusinessDetailsSuccess);
		//END ACTIVITY LOG

		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $user->id)->first();
		if(!$merchantContactDetail)
		{
			$merchantContactDetail = new MerchantContactDetail();
			$merchantContactDetail->merchant_id = $user->id;
		}
		$merchantContactDetail->owner_name = $request->owner_name;
		$merchantContactDetail->owner_email = $request->owner_email;
		$merchantContactDetail->owner_mobile_no = $request->owner_mobile_no;
		$merchantContactDetail->owner_address = $request->owner_address;
		$merchantContactDetail->owner_state = $request->owner_state;

		$city = City::where('city_name', '=', $request->owner_city)->first();
		if(!$city)
		{
			$city = new City();
			$city->city_name = $request->owner_city;
			$city->city_status = 'Active';
			$city->state_id = $request->business_state;
			$city->save();
		}
		$merchantContactDetail->owner_city = $city->id;
		$merchantContactDetail->owner_country = $request->owner_country;
		$merchantContactDetail->owner_pin_code = $request->owner_pin_code;
		$merchantContactDetail->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'owner_name' => $request->owner_name,
																			'owner_email' => $request->owner_email,
																			'owner_mobile_no' => $request->owner_mobile_no,
																			'owner_address' => $request->owner_address,
																			'owner_state' => $request->owner_state,
																			'owner_city' => $request->owner_city,
																			'owner_country' => $request->owner_country,
																			'owner_pin_code' => $request->owner_pin_code,
																	])->log($this->addContactDetailsSuccess);
		//END ACTIVITY LOG

		$merchantBankDetail = merchantBankDetail::where('merchant_id', $user->id)->first();
		if(!$merchantBankDetail)
		{
			$merchantBankDetail = new merchantBankDetail();
			$merchantBankDetail->merchant_id = $user->id;
		}
		$merchantBankDetail->bank_id = $request->bank_id;
		$merchantBankDetail->account_number = $request->account_number;
		$merchantBankDetail->bank_ifsc_code = $request->bank_ifsc_code;
		$merchantBankDetail->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'bank_id' => $request->bank_id,
																			'account_number' => $request->account_number,
																			'bank_ifsc_code' => $request->bank_ifsc_code,
																	])->log($this->addBankDetailsSuccess);
		//END ACTIVITY LOG

		$merchantWebsiteDetail = merchantWebsiteDetail::where('merchant_id', $user->id)->first();
		if(!$merchantWebsiteDetail)
		{
			$merchantWebsiteDetail = new merchantWebsiteDetail();
			$merchantWebsiteDetail->merchant_id = $user->id;
		}
		$merchantWebsiteDetail->domain_name = $request->domain_name;
		$merchantWebsiteDetail->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'domain_name' => $request->domain_name,
																	])->log($this->addWebsiteDetailsSuccess);
		//END ACTIVITY LOG

		Merchant::where('id', $user->id)->update(['last_activation_step' => 'step2']);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'last_activation_step' => 'step2',
																		],
																		'old' =>
																		[
																			'last_activation_step' => 'step1',
																		],
																	])->log($this->updateActivationStepSuccess);
		//END ACTIVITY LOG

		return redirect(route('merchants.activation.step3'));
	}
	/**
	 * END Do Process Activation Step 2
	 * @return redirect
	 */

	 /**
	  * Do Process Activation Step 3
	  * @return redirect
	  */
	public function processStep3(Request $request)
	{
		//ENABLE PERMISSION ON STORAGE FOLDER
		$path = storage_path('app');
		$command = 'sudo chmod -R 777 ' . $path;
		shell_exec($command);
		//END ENABLE PERMISSION ON STORAGE FOLDER

		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();

		$store_path = 'merchant_document/' . $user->id;
		$final_path = $this->file_path . $user->id . '/';

		if(!$merchantDocument)
		{
			$merchantDocument = new MerchantDocument();
			$merchantDocument->merchant_id = $user->id;
		}

		if ($request->hasFile('proprietor_pan_card_file'))
		{
			if ($request->file('proprietor_pan_card_file')->isValid())
			{
				$path = $request->proprietor_pan_card_file->store($store_path);

				$merchantDocument->proprietor_pan_card_file = basename($path);
				$merchantDocument->proprietor_pan_card_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('gumasta_file'))
		{
			if ($request->file('gumasta_file')->isValid())
			{
				$path = $request->gumasta_file->store($store_path);

				$merchantDocument->gumasta_file = basename($path);
				$merchantDocument->gumasta_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('gst_in_no_file'))
		{
			if ($request->file('gst_in_file')->isValid())
			{
				$path = $request->gst_in_file->store($store_path);

				$merchantDocument->gst_in_file = basename($path);
				$merchantDocument->gst_in_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('importer_exporter_code_file'))
		{
			if ($request->file('importer_exporter_code_file')->isValid())
			{
				$path = $request->importer_exporter_code_file->store($store_path);

				$merchantDocument->importer_exporter_code_file = basename($path);
				$merchantDocument->importer_exporter_code_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('passport_file'))
		{
			if ($request->file('passport_file')->isValid())
			{
				$path = $request->passport_file->store($store_path);

				$merchantDocument->passport_file = basename($path);
				$merchantDocument->passport_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('aadhar_card_file'))
		{
			if ($request->file('aadhar_card_file')->isValid())
			{
				$path = $request->aadhar_card_file->store($store_path);

				$merchantDocument->aadhar_card_file = basename($path);
				$merchantDocument->aadhar_card_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('driving_license_file'))
		{
			if ($request->file('driving_license_file')->isValid())
			{
				$path = $request->driving_license_file->store($store_path);

				$merchantDocument->driving_license_file = basename($path);
				$merchantDocument->driving_license_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('voter_id_card_file'))
		{
			if ($request->file('voter_id_card_file')->isValid())
			{
				$path = $request->voter_id_card_file->store($store_path);

				$merchantDocument->voter_id_card_file = basename($path);
				$merchantDocument->voter_id_card_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('property_tax_receipt_file'))
		{
			if ($request->file('property_tax_receipt_file')->isValid())
			{
				$path = $request->property_tax_receipt_file->store($store_path);

				$merchantDocument->property_tax_receipt_file = basename($path);
				$merchantDocument->property_tax_receipt_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('bank_canceled_cheque_file'))
		{
			if ($request->file('bank_canceled_cheque_file')->isValid())
			{
				$path = $request->bank_canceled_cheque_file->store($store_path);

				$merchantDocument->bank_canceled_cheque_file = basename($path);
				$merchantDocument->bank_canceled_cheque_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('audited_balance_sheet_file'))
		{
			if ($request->file('audited_balance_sheet_file')->isValid())
			{
				$path = $request->audited_balance_sheet_file->store($store_path);

				$merchantDocument->audited_balance_sheet_file = basename($path);
				$merchantDocument->audited_balance_sheet_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('current_account_statement_file'))
		{
			if ($request->file('current_account_statement_file')->isValid())
			{
				$path = $request->current_account_statement_file->store($store_path);

				$merchantDocument->current_account_statement_file = basename($path);
				$merchantDocument->current_account_statement_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('income_tax_return_file'))
		{
			if ($request->file('income_tax_return_file')->isValid())
			{
				$path = $request->income_tax_return_file->store($store_path);

				$merchantDocument->income_tax_return_file = basename($path);
				$merchantDocument->income_tax_return_path = $final_path . basename($path);
			}
		}

		if ($request->hasFile('kartpay_merchant_agreement_file'))
		{
			if ($request->file('kartpay_merchant_agreement_file')->isValid())
			{
				$path = $request->kartpay_merchant_agreement_file->store($store_path);

				$merchantDocument->kartpay_merchant_agreement_file = basename($path);
				$merchantDocument->kartpay_merchant_agreement_path = $final_path . basename($path);
			}
		}

		$merchantDocument->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([

																	])->log($this->addMerchantDocumentSuccess);
		//END ACTIVITY LOG

		Merchant::where('id', $user->id)->update(['last_activation_step' => 'step3']);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'last_activation_step' => 'step3',
																		],
																		'old' =>
																		[
																			'last_activation_step' => 'step2',
																		],
																	])->log($this->updateActivationStepSuccess);
		//END ACTIVITY LOG

		return redirect(route('merchants.activation.step4'));
	}
	/**
	 * END Do Process Activation Step 3
	 * @return redirect
	 */

	 /**
	  * Do Process Activation Step 4
	  * @return redirect
	  */
	public function processStep4(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		Merchant::where('id', $user->id)->update(['last_activation_step' => 'step4']);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'last_activation_step' => 'step4',
																		],
																		'old' =>
																		[
																			'last_activation_step' => 'step3',
																		],
																	])->log($this->updateActivationStepSuccess);
		//END ACTIVITY LOG

		return redirect(route('merchants.dashboard.merchant'));
	}
	/**
	 * END Do Process Activation Step 4
	 * @return redirect
	 */

	 /**
	  * Get States by Country
	  * @return response (json)
	  */
	function getStateByCountry(Request $request)
	{
		$country = Country::where('id', $request->country_id)->first();
		$responseStates = '';
		if(isset($country->states)) $responseStates = $country->states;

		return Response::json(
		  [
			'response' => 'Success',
			'states' => $responseStates
		  ]
		);
	}
	/**
	 * END Get States by Country
	 * @return response (json)
	 */

	 /**
	  * Get City by State
	  * @return response (json)
	  */
	function getCityByState(Request $request)
	{
		$state = State::where('id', $request->state_id)->first();
		$responseCities = '';
		if(isset($state->cities)) $responseCities = $state->cities;

		return Response::json(
		  [
			'response' => 'Success',
			'cities' => $responseCities
		  ]
		);
	}
	/**
	 * END Get City by State
	 * @return response (json)
	 */

	 /**
	  * Get City by State with Autocomplete
	  * @return response (json)
	  */
	function getCityByStateAutoComplete(Request $request)
	{
		$state = State::where('id', $request->state_id)->first();
		$citys = City::where('state_id', $state->id)->where('city_name', 'like', $request->city_name . '%')->get();

		$arrayCity = array();
		$arrayCityRow = array();
		foreach($citys as $city)
		{
			$arrayCityRow["value"] = $city->city_name;
			array_push($arrayCity, $arrayCityRow);
		}
		return json_encode($arrayCity);
	}
	/**
	 * END Get City by State with Autocomplete
	 * @return response (json)
	 */

	 /**
	  * Display Document Approval Status
	  * @return view
	  */
	public function documentApprovalStatus()
	{
		$id = Auth::guard('merchant')->user()->id;
		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $id)->first();
		$merchantDocument = MerchantDocument::where('merchant_id', $id)->first();
		$filePath = $this->file_path . $id . '/';
		$status = true;

		return view('merchant.backend.pages.activation.document_approval_status', compact('merchantBusinessDetail', 'merchantContactDetail', 'merchantDocument', 'filePath', 'status'));
	}
	/**
	 * END Display Document Approval Status
	 * @return view
	 */

	 /**
	  * Do Upload Merchant Document
	  * @return response (json)
	  */
	public function uploadMerchantDocument(Request $request)
	{
		//ENABLE PERMISSION ON STORAGE FOLDER
		$path = storage_path('app');
		$command = 'sudo chmod -R 777 ' . $path;
		shell_exec($command);
		//END ENABLE PERMISSION ON STORAGE FOLDER

		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();
		$file = '';
		$title = '';
		$store_path = 'merchant_document/' . $user->id;
		$final_path = $this->file_path . $user->id . '/';
		if(!$merchantDocument)
		{
			$merchantDocument = new MerchantDocument();
			$merchantDocument->merchant_id = $user->id;
		}
		if ($request->hasFile('file'))
		{
			if ($request->file('file')->isValid())
			{
				if($request->type == 'proprietor_pan_card')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'proprietor_pan_card_file' => basename($path),
																					],
																					'old' =>
																					[
																						'proprietor_pan_card_file' => $merchantDocument->proprietor_pan_card_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->proprietor_pan_card_file = basename($path);
					$merchantDocument->proprietor_pan_card_path = $final_path . basename($path);
					$merchantDocument->proprietor_pan_card_is_verified = null;
					$merchantDocument->proprietor_pan_card_is_received = null;
					$file = basename($path);
					$title = 'Proprietor Pan Card';
				}
				if($request->type == 'gumasta')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'gumasta_file' => basename($path),
																					],
																					'old' =>
																					[
																						'gumasta_file' => $merchantDocument->gumasta_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->gumasta_file = basename($path);
					$merchantDocument->gumasta_path = $final_path . basename($path);
					$merchantDocument->gumasta_is_verified = null;
					$merchantDocument->gumasta_is_received = null;
					$file = basename($path);
					$title = 'Shop and Establishment Certifcate.(Gumasta)';
				}

				if($request->type == 'gst_in')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'gst_in_file' => basename($path),
																					],
																					'old' =>
																					[
																						'gst_in_file' => $merchantDocument->gst_in_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->gst_in_file = basename($path);
					$merchantDocument->gst_in_path = $final_path . basename($path);
					$merchantDocument->gst_in_is_verified = null;
					$merchantDocument->gst_in_is_received = null;
					$file = basename($path);
					$title = 'GSTIN ID';
				}

				if($request->type == 'importer_exporter_code')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'importer_exporter_code_file' => basename($path),
																					],
																					'old' =>
																					[
																						'importer_exporter_code_file' => $merchantDocument->importer_exporter_code_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->importer_exporter_code_file = basename($path);
					$merchantDocument->importer_exporter_code_path = $final_path . basename($path);
					$merchantDocument->importer_exporter_code_is_verified = null;
					$merchantDocument->importer_exporter_code_is_received = null;
					$file = basename($path);
					$title = 'Importer/Exporter Code';
				}
				if($request->type == 'passport')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'passport_file' => basename($path),
																					],
																					'old' =>
																					[
																						'passport_file' => $merchantDocument->passport_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->passport_file = basename($path);
					$merchantDocument->passport_path = $final_path . basename($path);
					$merchantDocument->passport_is_verified = null;
					$merchantDocument->passport_is_received = null;
					$file = basename($path);
					$title = 'Passport';
				}
				if($request->type == 'aadhar_card')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'aadhar_card_file' => basename($path),
																					],
																					'old' =>
																					[
																						'aadhar_card_file' => $merchantDocument->aadhar_card_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->aadhar_card_file = basename($path);
					$merchantDocument->aadhar_card_path = $final_path . basename($path);
					$merchantDocument->aadhar_card_is_verified = null;
					$merchantDocument->aadhar_card_is_received = null;
					$file = basename($path);
					$title = 'Aadhar Card';
				}
				if($request->type == 'driving_license')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'driving_license_file' => basename($path),
																					],
																					'old' =>
																					[
																						'driving_license_file' => $merchantDocument->driving_license_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->driving_license_file = basename($path);
					$merchantDocument->driving_license_path = $final_path . basename($path);
					$merchantDocument->driving_license_is_verified = null;
					$merchantDocument->driving_license_is_received = null;
					$file = basename($path);
					$title = 'Driving License';
				}
				if($request->type == 'voter_id_card')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'voter_id_card_file' => basename($path),
																					],
																					'old' =>
																					[
																						'voter_id_card_file' => $merchantDocument->voter_id_card_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->voter_id_card_file = basename($path);
					$merchantDocument->voter_id_card_path = $final_path . basename($path);
					$merchantDocument->voter_id_card_is_verified = null;
					$merchantDocument->voter_id_card_is_received = null;
					$file = basename($path);
					$title = 'Voter ID Card';
				}
				if($request->type == 'property_tax_receipt')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'property_tax_receipt_file' => basename($path),
																					],
																					'old' =>
																					[
																						'property_tax_receipt_file' => $merchantDocument->property_tax_receipt_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->property_tax_receipt_file = basename($path);
					$merchantDocument->property_tax_receipt_path = $final_path . basename($path);
					$merchantDocument->property_tax_receipt_is_verified = null;
					$merchantDocument->property_tax_receipt_is_received = null;
					$file = basename($path);
					$title = 'Property TAX Receipt in Proprietor Name';
				}
				if($request->type == 'bank_canceled_cheque')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'bank_canceled_cheque_file' => basename($path),
																					],
																					'old' =>
																					[
																						'bank_canceled_cheque_file' => $merchantDocument->bank_canceled_cheque_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->bank_canceled_cheque_file = basename($path);
					$merchantDocument->bank_canceled_cheque_path = $final_path .  basename($path);
					$merchantDocument->bank_canceled_cheque_is_verified = null;
					$merchantDocument->bank_canceled_cheque_is_received = null;
					$file = basename($path);
					$title = 'Bank Canceled Cheque.(Compulsory)';
				}
				if($request->type == 'audited_balance_sheet')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'audited_balance_sheet_file' => basename($path),
																					],
																					'old' =>
																					[
																						'audited_balance_sheet_file' => $merchantDocument->audited_balance_sheet_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->audited_balance_sheet_file = basename($path);
					$merchantDocument->audited_balance_sheet_path = $final_path .  basename($path);
					$merchantDocument->audited_balance_sheet_is_verified = null;
					$merchantDocument->audited_balance_sheet_is_received = null;
					$file = basename($path);
					$title = 'Audited Balance Sheet';
				}
				if($request->type == 'current_account_statement')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'current_account_statement_file' => basename($path),
																					],
																					'old' =>
																					[
																						'current_account_statement_file' => $merchantDocument->current_account_statement_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->current_account_statement_file = basename($path);
					$merchantDocument->current_account_statement_path = $final_path .  basename($path);
					$merchantDocument->current_account_statement_is_verified = null;
					$merchantDocument->current_account_statement_is_received = null;
					$file = basename($path);
					$title = 'Current Account Statement. (Last 3 Month)';
				}
				if($request->type == 'income_tax_return')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'income_tax_return_file' => basename($path),
																					],
																					'old' =>
																					[
																						'income_tax_return_file' => $merchantDocument->income_tax_return_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->income_tax_return_file = basename($path);
					$merchantDocument->income_tax_return_path = $final_path .  basename($path);
					$merchantDocument->income_tax_return_is_verified = null;
					$merchantDocument->income_tax_return_is_received = null;
					$file = basename($path);
					$title = 'Income tax Returns. (Last Year)';
				}
				if($request->type == 'kartpay_merchant_agreement')
				{
					$path = $request->file('file')->store($store_path);

					//ACTIVITY LOG
					activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																					'attributes' =>
																					[
																						'kartpay_merchant_agreement_file' => basename($path),
																					],
																					'old' =>
																					[
																						'kartpay_merchant_agreement_file' => $merchantDocument->kartpay_merchant_agreement_file,
																					],
																				])->log($this->addMerchantDocumentSuccess);
					//END ACTIVITY LOG

					$merchantDocument->kartpay_merchant_agreement_file = basename($path);
					$merchantDocument->kartpay_merchant_agreement_path = $final_path .  basename($path);
					$merchantDocument->kartpay_merchant_agreement_is_verified = null;
					$merchantDocument->kartpay_merchant_agreement_is_received = null;
					$file = basename($path);
					$title = 'Kartpay Merchant Agreement';
				}
			}
		}

		$merchantDocument->save();

		return Response::json([
								'response' => 'Success',
								'type' => $request->type,
								'file' => $file,
								'title' => $title
							  ]);
	}
	/**
	 * END Do Upload Merchant Document
	 * @return response (json)
	 */

	 /**
	  * Remove Uploaded Merchant Document
	  * @return response (json)
	  */
	public function removeMerchantDocument(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();

		if($request->type == 'proprietor_pan_card')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->proprietor_pan_card_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'proprietor_pan_card_file' => null,
																			],
																			'old' =>
																			[
																				'proprietor_pan_card_file' => $merchantDocument->proprietor_pan_card_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->proprietor_pan_card_file = null;
			$merchantDocument->proprietor_pan_card_path = null;
		}

		if($request->type == 'gumasta')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->gumasta_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'gumasta_file' => null,
																			],
																			'old' =>
																			[
																				'gumasta_file' => $merchantDocument->gumasta_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->gumasta_file = null;
			$merchantDocument->gumasta_path = null;
		}

		if($request->type == 'gst_in')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->gst_in_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'gst_in_file' => null,
																			],
																			'old' =>
																			[
																				'gst_in_file' => $merchantDocument->gst_in_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->gst_in_file = null;
			$merchantDocument->gst_in_path = null;
		}

		if($request->type == 'importer_exporter_code')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->importer_exporter_code_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'importer_exporter_code_file' => null,
																			],
																			'old' =>
																			[
																				'importer_exporter_code_file' => $merchantDocument->importer_exporter_code_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->importer_exporter_code_file = null;
			$merchantDocument->importer_exporter_code_path = null;
		}

		if($request->type == 'passport')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->passport_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'passport_file' => null,
																			],
																			'old' =>
																			[
																				'passport_file' => $merchantDocument->passport_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->passport_file = null;
			$merchantDocument->passport_path = null;
		}

		if($request->type == 'aadhar_card')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->aadhar_card_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'aadhar_card_file' => null,
																			],
																			'old' =>
																			[
																				'aadhar_card_file' => $merchantDocument->aadhar_card_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->aadhar_card_file = null;
			$merchantDocument->aadhar_card_path = null;
		}

		if($request->type == 'driving_license')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->driving_license_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'driving_license_file' => null,
																			],
																			'old' =>
																			[
																				'driving_license_file' => $merchantDocument->driving_license_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->driving_license_file = null;
			$merchantDocument->driving_license_path = null;
		}

		if($request->type == 'voter_id_card')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->voter_id_card_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'voter_id_card_file' => null,
																			],
																			'old' =>
																			[
																				'voter_id_card_file' => $merchantDocument->voter_id_card_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->voter_id_card_file = null;
			$merchantDocument->voter_id_card_path = null;
		}

		if($request->type == 'property_tax_receipt')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->property_tax_receipt_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'property_tax_receipt_file' => null,
																			],
																			'old' =>
																			[
																				'property_tax_receipt_file' => $merchantDocument->property_tax_receipt_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->property_tax_receipt_file = null;
			$merchantDocument->property_tax_receipt_path = null;
		}

		if($request->type == 'bank_canceled_cheque')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->bank_canceled_cheque_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'bank_canceled_cheque_file' => null,
																			],
																			'old' =>
																			[
																				'bank_canceled_cheque_file' => $merchantDocument->bank_canceled_cheque_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->bank_canceled_cheque_file = null;
			$merchantDocument->bank_canceled_cheque_path = null;
		}

		if($request->type == 'audited_balance_sheet')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->audited_balance_sheet_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'audited_balance_sheet_file' => null,
																			],
																			'old' =>
																			[
																				'audited_balance_sheet_file' => $merchantDocument->audited_balance_sheet_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->audited_balance_sheet_file = null;
			$merchantDocument->audited_balance_sheet_path = null;
		}

		if($request->type == 'current_account_statement')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->current_account_statement_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'current_account_statement_file' => null,
																			],
																			'old' =>
																			[
																				'current_account_statement_file' => $merchantDocument->current_account_statement_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->current_account_statement_file = null;
			$merchantDocument->current_account_statement_path = null;
		}

		if($request->type == 'income_tax_return')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->income_tax_return_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'income_tax_return_file' => null,
																			],
																			'old' =>
																			[
																				'income_tax_return_file' => $merchantDocument->income_tax_return_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->income_tax_return_file = null;
			$merchantDocument->income_tax_return_path = null;
		}

		if($request->type == 'kartpay_merchant_agreement')
		{
			@unlink(storage_path($this->file_storage . $user->id . '/' . $merchantDocument->kartpay_merchant_agreement_file));

			//ACTIVITY LOG
			activity($this->eventUpdateMerchantDocument)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'kartpay_merchant_agreement_file' => null,
																			],
																			'old' =>
																			[
																				'kartpay_merchant_agreement_file' => $merchantDocument->kartpay_merchant_agreement_file,
																			],
																		])->log($this->removeMerchantDocumentSuccess);
			//END ACTIVITY LOG

			$merchantDocument->kartpay_merchant_agreement_file = null;
			$merchantDocument->kartpay_merchant_agreement_path = null;
		}

		$merchantDocument->save();

		return Response::json([
								'response' => 'Success',
								'type' => $request->type
							  ]);
	}
	/**
	 * END Remove Uploaded Merchant Document
	 * @return response (json)
	 */

	 /**
	  * Load Uploaded Merchant Document
	  * @return response (json)
	  */
	public function getMerchantDocument(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();

		if(!$merchantDocument)
		{
			return Response::json([
								'response' => 'Error',
								'message' => 'No Data'
							  ]);
		}
		else
		{
			return Response::json([
								'response' => 'Success',
								'message' => 'Available',
								'proprietor_pan_card_file' => $merchantDocument->proprietor_pan_card_file,
								'proprietor_pan_card_is_verified' => $merchantDocument->proprietor_pan_card_is_verified,
								'gumasta_file' => $merchantDocument->gumasta_file,
								'gumasta_is_verified' => $merchantDocument->gumasta_is_verified,
								'gst_in_file' => $merchantDocument->gst_in_file,
								'gst_in_is_verified' => $merchantDocument->gst_in_is_verified,
								'excise_registration_no_file' => $merchantDocument->excise_registration_no_file,
								'excise_registration_is_verified' => $merchantDocument->excise_registration_is_verified,
								'importer_exporter_code_file' => $merchantDocument->importer_exporter_code_file,
								'importer_exporter_code_is_verified' => $merchantDocument->importer_exporter_code_is_verified,
								'passport_file' => $merchantDocument->passport_file,
								'passport_is_verified' => $merchantDocument->passport_is_verified,
								'aadhar_card_file' => $merchantDocument->aadhar_card_file,
								'aadhar_card_is_verified' => $merchantDocument->aadhar_card_is_verified,
								'driving_license_file' => $merchantDocument->driving_license_file,
								'driving_license_is_verified' => $merchantDocument->driving_license_is_verified,
								'voter_id_card_file' => $merchantDocument->voter_id_card_file,
								'voter_id_card_is_verified' => $merchantDocument->voter_id_card_is_verified,
								'property_tax_receipt_file' => $merchantDocument->property_tax_receipt_file,
								'property_tax_receipt_is_verified' => $merchantDocument->property_tax_receipt_is_verified,
								'bank_canceled_cheque_file' => $merchantDocument->bank_canceled_cheque_file,
								'bank_canceled_cheque_is_verified' => $merchantDocument->bank_canceled_cheque_is_verified,
								'audited_balance_sheet_file' => $merchantDocument->audited_balance_sheet_file,
								'audited_balance_sheet_is_verified' => $merchantDocument->audited_balance_sheet_is_verified,
								'current_account_statement_file' => $merchantDocument->current_account_statement_file,
								'current_account_statement_is_verified' => $merchantDocument->current_account_statement_is_verified,
								'income_tax_return_file' => $merchantDocument->income_tax_return_file,
								'income_tax_return_is_verified' => $merchantDocument->income_tax_return_file,
								'kartpay_merchant_agreement_file' => $merchantDocument->kartpay_merchant_agreement_file,
								'kartpay_merchant_agreement_is_verified' => $merchantDocument->kartpay_merchant_agreement_is_verified
							  ]);
		}
	}
	/**
	 * END Load Uploaded Merchant Document
	 * @return response (json)
	 */

	 /**
	  * Load Settlement
	  * @return view
	  */
	public function settlement(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$document = Auth::guard('merchant')->user()->merchantDocument;

		$merchantDocumentCourier = null;
		if($document)
		{
			$merchantDocumentCourier = MerchantDocumentCourier::where('merchant_document_id', $document->id)->orderBy('created_at', 'desc')->first();
		}
		$couriers = Couriers::all();
		$filePath = $this->file_path . $user->id . '/';

		return view('merchant.backend.pages.activation.settlement', compact('document', 'couriers', 'merchantDocumentCourier', 'filePath'));
	}
	/**
	 * END Load Settlement
	 * @return view
	 */

	 /**
	  * Do Process Settlement
	  * @return view
	  */
	public function processSettlement(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();
		$merchantDocument->courier_id = $request->courier_id;
		$merchantDocument->courier_tracking_id = $request->courier_tracking_id;
		$merchantDocument->is_admin_approval == '0';

		//SET NULL IF NOT RECEIVED
		if($merchantDocument->proprietor_pan_card_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->proprietor_pan_card_is_received = null;
		}
		if($merchantDocument->gumasta_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->gumasta_is_received = null;
		}
		if($merchantDocument->gst_in_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->gst_in_is_received = null;
		}
		if($merchantDocument->importer_exporter_code_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->importer_exporter_code_is_received = null;
		}
		if($merchantDocument->passport_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->passport_is_received = null;
		}
		if($merchantDocument->aadhar_card_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->aadhar_card_is_received = null;
		}
		if($merchantDocument->driving_license_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->driving_license_is_received = null;
		}
		if($merchantDocument->voter_id_card_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->voter_id_card_is_received = null;
		}
		if($merchantDocument->property_tax_receipt_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->property_tax_receipt_is_received = null;
		}
		if($merchantDocument->bank_canceled_cheque_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->bank_canceled_cheque_is_received = null;
		}
		if($merchantDocument->audited_balance_sheet_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->audited_balance_sheet_is_received = null;
		}
		if($merchantDocument->current_account_statement_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->current_account_statement_is_received = null;
		}
		if($merchantDocument->income_tax_return_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->income_tax_return_is_received = null;
		}
		if($merchantDocument->kartpay_merchant_agreement_is_received == 'NOT RECEIVED')
		{
			$merchantDocument->kartpay_merchant_agreement_is_received = null;
		}

		//END SET NULL IF NOT RECEIVED

		$merchantDocument->save();

		$merchantDocumentCourier = MerchantDocumentCourier::where('merchant_document_id', $merchantDocument->id)
															->where('courier_id', $request->courier_id)
															->where('courier_tracking_id', $request->courier_tracking_id)
															->first();
		if(!$merchantDocumentCourier)
		{
			$merchantDocumentCourier = new MerchantDocumentCourier();
			$merchantDocumentCourier->merchant_document_id = $merchantDocument->id;
			$merchantDocumentCourier->courier_id = $request->courier_id;
			$merchantDocumentCourier->courier_tracking_id = $request->courier_tracking_id;
			$merchantDocumentCourier->save();
		}

		return back()->with('message', 'Congratulations! Your Tracking ID has been saved, we would keep track your documents for faster activation of your account for settlements.');
	}
	/**
	 * END Do Process Settlement
	 * @return view
	 */

	 /**
	  * Load Not Receive Document
	  * @return view
	  */
	public function not_receive_document(Request $request)
	{
		$user = Auth::guard('merchant')->user();

		$document = Auth::guard('merchant')->user()->merchantDocument;
		$filePath = $this->file_path . $user->id . '/';

		$merchantDocumentCourier = null;
		if($document)
		{
			$merchantDocumentCourier = MerchantDocumentCourier::where('merchant_document_id', $document->id)->orderBy('created_at', 'desc')->first();
		}
		$couriers = Couriers::all();

		return view('merchant.backend.pages.activation.not_receive_document', compact('document', 'couriers', 'merchantDocumentCourier', 'filePath'));
	}
	/**
	 * END Load Not Receive Document
	 * @return view
	 */
}
