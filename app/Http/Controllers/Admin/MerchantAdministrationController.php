<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Merchant;
use App\MerchantBusinessDetail;
use App\MerchantContactDetail;
use App\MerchantBankDetail;
use App\MerchantWebsiteDetail;
use App\MerchantDocument;
use App\MerchantPersonalInformation;
use App\OtpGenerator;
use App\AccessKey;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantAdministrationController extends RegisterController
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Merchant Administration';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Merchant updated successfuly';
	protected $updateBankDetailsSuccess = 'Merchant bank details updated successfuly';
	protected $updateBusinessInformationSuccess = 'Merchant business information updated successfuly';
	protected $updatePersonalInformationSuccess = 'Merchant personal information updated successfuly';
	protected $updateAvatarSuccess = 'Merchant avatar updated successfuly';
	protected $resendOtp = 'Merchant request to resend new OTP';
	protected $verificationOtpSuccess = 'Merchant verified by OTP successfully';
	protected $verifiedDocumentSuccess = 'User verified a merchant document successfuly';
	protected $unverifiedDocumentSuccess = 'User unverified a merchant document successfuly';
	protected $rejectedDocumentSuccess = 'User rejected a merchant document successfuly';

	protected $file_path = 'file/merchant_document/';

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
      * Load Merchant Administration
      *
      */
	public function merchantAdministration()
	{
		return view('panel.backend.pages.merchant.merchant_administration');
	}
	/**
		 * END Load Merchant Administration
		 *
		 */

	 /**
 		 * Display List of Merchants
 		 *
 		 */
	public function display()
	{
		$users = Merchant::where('merchant_id', '0')->get();
		$userType = Auth::guard('admin')->user()->type;

		$newUsers = array();
		$x = 1;
		foreach($users as $user)
		{
			$viewlink =  url('/merchant_administration/' . $user->id . '/document_approval_step1');
			$deletelink =  route('admins.merchant_administration_destroy', ['id' => $user->id ]);

			if($user->live_domain_active == '1') $viewlink =  route('admins.merchant_administration_show', ['id' => $user->id]);

			$actions = '<a href="'.$viewlink.'" class="btn btn-xs btn-success" data-toggle="tooltip" title="View merchant ' . $user->first_name . ' ' . $user->last_name . '"><i class="fa fa-eye"></i></a>&nbsp;';

			$blkButton = '<input type="submit" data-id="' . $user->id . '" name="block" value="Block" class="btn btn-xs btn-danger">';
			if($user->is_blocked) $blkButton = '<input type="submit" data-id="' . $user->id . '" name="block" value="Unblock" class="btn btn-xs btn-success">';
			$actions .= $blkButton;

			//Only Super Admin allow delete merchant
			if($userType == 'panel')
			{
				$actions .= '<a class="btn btn-xs btn-danger" data-toggle="tooltip" title="Delete merchant ' . $user->first_name . ' ' . $user->last_name . '" onclick="DeleteConfirm(\'' . $user->email . '\', \'' . $deletelink . '\')"><i class="fa fa-trash-o"></i></a>&nbsp;';
			}
			//END Only Super Admin allow delete user

			$objUsers = array();
			$objUsers[] = $x;
			$objUsers[] = $user->first_name;
			$objUsers[] = $user->last_name;
			$objUsers[] = $user->country_code . ' ' . $user->contact_no;
			$objUsers[] = $user->email;

			$status = '<span class="label label-success">Active</span>';
			if($user->is_blocked)
			{
				$status = '<span class="label label-danger">Blocked</span>';
			}
			else
			{
				if($user->is_active == '0') $status = '<span class="label label-danger">Not Active</span>';
			}
			$objUsers[] = $status;

			$liveDomainActive = '<span class="label label-success">Active</span>';
			if($user->live_domain_active == '0') $liveDomainActive = '<span class="label label-danger">Not Active</span>';
			$objUsers[] = $liveDomainActive;
			$objUsers[] = $actions;
			$newUsers[] = $objUsers;

			$x++;
		}
		return array( 'data' => $newUsers );
	}
	/**
		* END Display List of Merchants
		*
		*/

	/**
		* Show Merchant Details
		*
		*/
	public function show($id)
  {
		$user = Merchant::find($id);

		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $user->id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $user->id)->first();
		$merchantBankDetail = MerchantBankDetail::where('merchant_id', $user->id)->first();
		$merchantWebsiteDetail = MerchantWebsiteDetail::where('merchant_id', $user->id)->first();
		$merchantDocument = MerchantDocument::where('merchant_id', $user->id)->first();

		$filePath = $this->file_path . $user->id . '/';

		return view('panel.backend.pages.merchant.merchant_administration_profile_new', compact('user', 'merchantBusinessDetail', 'merchantContactDetail', 'merchantBankDetail', 'merchantWebsiteDetail', 'merchantDocument', 'filePath'));
  }
	/**
		* END Show Merchant Details
		*
		*/

	/**
		* Update Merchant Details
		* @param = merchant id
		*/
	public function update($id, Request $request)
  {
		$user = Merchant::find($id);

		$oldEmail               = $user->email;
    $validationParam        = '';
    $rEmail                 = $request->email;

    if ($rEmail != $user->email) :
        $validationParam    = '|unique:users';
    endif;

    $this->validate($request, [
        'first_name'      => 'required|max:100',
        'last_name'      => 'required|max:100',
        'country_code'      => 'required|min:3',
        'contact_no'      => 'required|max:10',
        'email'           => 'email|required|max:100'.$validationParam
    ]);

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
		$user->is_active = $request->is_active;
		$user->live_domain_active = $request->live_domain_active;
		$user->save();

		return redirect(route('admins.merchant_administration_show', ['id' => $user->id]))->with(
                'message',
                'User <a href="'.route('admins.merchant_administration_show', ['id' => $user->id]).'">'.$user->first_name .' ' .$user->last_name . ' ('.$user->email.')</a> was updated.'
            );
	}
	/**
		* END Update Merchant Details
		* @param = merchant id
		*/

	/**
		* Update Merchant Avatar
		* @param = merchant id
		*/
	public function updateAvatar($id, Request $request)
  {
		$imageFileName = $this->saveImage($request->avatar);

		$user = Merchant::find($id);

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

		$user->avatar_file_name = 'p' . $this->saveImage($request->avatar);
        $user->update();
        return 'AVATAR_UPDATED';
  }
	/**
		* END Update Merchant Avatar
		* @param = merchant id
		*/

	/**
		* Load Merchant Approval Document Step 1
		* @param = merchant id
		*/
	public function documentApprovalStep1($id)
	{
		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $id)->first();
		$merchantDocument = MerchantDocument::where('merchant_id', $id)->first();

		$totalDocument = 0;
		$totalApproved = 0;
		if($merchantDocument)
		{
			if($merchantDocument->proprietor_pan_card_file != null)
			{
				$totalDocument++;
				if($merchantDocument->proprietor_pan_card_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->gumasta_file != null)
			{
				$totalDocument++;
				if($merchantDocument->gumasta_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->gst_in_file != null)
			{
				$totalDocument++;
				if($merchantDocument->gst_in_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->importer_exporter_code_file != null)
			{
				$totalDocument++;
				if($merchantDocument->importer_exporter_code_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->passport_file != null)
			{
				$totalDocument++;
				if($merchantDocument->passport_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->aadhar_card_file != null)
			{
				$totalDocument++;
				if($merchantDocument->aadhar_card_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->driving_license_file != null)
			{
				$totalDocument++;
				if($merchantDocument->driving_license_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->voter_id_card_file != null)
			{
				$totalDocument++;
				if($merchantDocument->voter_id_card_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->property_tax_receipt_file != null)
			{
				$totalDocument++;
				if($merchantDocument->property_tax_receipt_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->bank_canceled_cheque_file != null)
			{
				$totalDocument++;
				if($merchantDocument->bank_canceled_cheque_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->audited_balance_sheet_file != null)
			{
				$totalDocument++;
				if($merchantDocument->audited_balance_sheet_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->current_account_statement_file != null)
			{
				$totalDocument++;
				if($merchantDocument->current_account_statement_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->income_tax_return_file != null)
			{
				$totalDocument++;
				if($merchantDocument->income_tax_return_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
			if($merchantDocument->kartpay_merchant_agreement_file != null)
			{
				$totalDocument++;
				if($merchantDocument->kartpay_merchant_agreement_is_verified == 'APPROVE')
				{
					$totalApproved++;
				}
			}
		}

		if($totalDocument == $totalApproved) return redirect(route('admins.merchant_administration.document_approval_step2', ['id' => $id]));

		$filePath = $this->file_path . $id . '/';

		return view('panel.backend.pages.merchant.merchant_administration_document_approval_step1', compact('merchantBusinessDetail', 'merchantContactDetail', 'merchantDocument', 'filePath'));
	}
	/**
		* END Load Merchant Approval Document Step 1
		* @param = merchant id
		*/

	/**
		* Process Merchant Approval Document Step 1
		* @param = merchant id
		*/
	public function processDocumentApprovalStep1($id, Request $request)
	{
		$merchantDocument = MerchantDocument::where('merchant_id', $id)->first();

		if($request->proprietor_pan_card_is_verified != null) $merchantDocument->proprietor_pan_card_is_verified = $request->proprietor_pan_card_is_verified;
		if($request->gumasta_is_verified != null) $merchantDocument->gumasta_is_verified = $request->gumasta_is_verified;
		if($request->gst_in_is_verified != null) $merchantDocument->gst_in_is_verified = $request->gst_in_is_verified;
		if($request->importer_exporter_code_is_verified != null) $merchantDocument->importer_exporter_code_is_verified = $request->importer_exporter_code_is_verified;
		if($request->passport_is_verified != null) $merchantDocument->passport_is_verified = $request->passport_is_verified;
		if($request->aadhar_card_is_verified != null) $merchantDocument->aadhar_card_is_verified = $request->aadhar_card_is_verified;
		if($request->driving_license_is_verified != null) $merchantDocument->driving_license_is_verified = $request->driving_license_is_verified;
		if($request->voter_id_card_is_verified != null) $merchantDocument->voter_id_card_is_verified = $request->voter_id_card_is_verified;
		if($request->property_tax_receipt_is_verified != null) $merchantDocument->property_tax_receipt_is_verified = $request->property_tax_receipt_is_verified;
		if($request->bank_canceled_cheque_is_verified != null) $merchantDocument->bank_canceled_cheque_is_verified = $request->bank_canceled_cheque_is_verified;
		if($request->audited_balance_sheet_is_verified != null) $merchantDocument->audited_balance_sheet_is_verified = $request->audited_balance_sheet_is_verified;
		if($request->current_account_statement_is_verified != null) $merchantDocument->current_account_statement_is_verified = $request->current_account_statement_is_verified;
		if($request->income_tax_return_is_verified != null) $merchantDocument->income_tax_return_is_verified = $request->income_tax_return_is_verified;
		if($request->kartpay_merchant_agreement_is_verified != null) $merchantDocument->kartpay_merchant_agreement_is_verified = $request->kartpay_merchant_agreement_is_verified;

		$merchantDocument->save();
		return redirect(route('admins.merchant_administration'));
	}
	/**
		* END Process Merchant Approval Document Step 1
		* @param = merchant id
		*/

	/**
		* Load Merchant Approval Document Step 2
		* @param = merchant id
		*/
	public function documentApprovalStep2($id)
	{
		$merchantBusinessDetail = MerchantBusinessDetail::where('merchant_id', $id)->first();
		$merchantContactDetail = MerchantContactDetail::where('merchant_id', $id)->first();
		$merchantDocument = MerchantDocument::where('merchant_id', $id)->first();

		$filePath = $this->file_path . $id . '/';

		return view('panel.backend.pages.merchant.merchant_administration_document_approval_step2', compact('merchantBusinessDetail', 'merchantContactDetail', 'merchantDocument', 'filePath'));
	}
	/**
		* END Load Merchant Approval Document Step 2
		* @param = merchant id
		*/

	/**
		* Process Merchant Approval Document Step 2
		* @param = merchant id
		*/
	public function processDocumentApprovalStep2($id, Request $request)
	{
		$merchantDocument = MerchantDocument::where('merchant_id', $id)->first();

		$total_doc = 0;
		$total_received = 0;

		if($request->proprietor_pan_card_is_received != null)
		{
			$merchantDocument->proprietor_pan_card_is_received = $request->proprietor_pan_card_is_received;
			$total_doc++;
			if($request->proprietor_pan_card_is_received == 'RECEIVED') $total_received++;
		}
		if($request->gumasta_is_received != null)
		{
			$merchantDocument->gumasta_is_received = $request->gumasta_is_received;
			$total_doc++;
			if($request->gumasta_is_received == 'RECEIVED') $total_received++;
		}
		if($request->gst_in_is_received != null)
		{
			$merchantDocument->gst_in_is_received = $request->gst_in_is_received;
			$total_doc++;
			if($request->gst_in_is_received == 'RECEIVED') $total_received++;
		}
		if($request->importer_exporter_code_is_received != null)
		{
			$merchantDocument->importer_exporter_code_is_received = $request->importer_exporter_code_is_received;
			$total_doc++;
			if($request->importer_exporter_code_is_received == 'RECEIVED') $total_received++;
		}
		if($request->passport_is_received != null)
		{
			$merchantDocument->passport_is_received = $request->passport_is_received;
			$total_doc++;
			if($request->passport_is_received == 'RECEIVED') $total_received++;
		}
		if($request->aadhar_card_is_received != null)
		{
			$merchantDocument->aadhar_card_is_received = $request->aadhar_card_is_received;
			$total_doc++;
			if($request->aadhar_card_is_received == 'RECEIVED') $total_received++;
		}
		if($request->driving_license_is_received != null)
		{
			$merchantDocument->driving_license_is_received = $request->driving_license_is_received;
			$total_doc++;
			if($request->driving_license_is_received == 'RECEIVED') $total_received++;
		}
		if($request->voter_id_card_is_received != null)
		{
			$merchantDocument->voter_id_card_is_received = $request->voter_id_card_is_received;
			$total_doc++;
			if($request->voter_id_card_is_received == 'RECEIVED') $total_received++;
		}
		if($request->property_tax_receipt_is_received != null)
		{
			$merchantDocument->property_tax_receipt_is_received = $request->property_tax_receipt_is_received;
			$total_doc++;
			if($request->property_tax_receipt_is_received == 'RECEIVED') $total_received++;
		}
		if($request->bank_canceled_cheque_is_received != null)
		{
			$merchantDocument->bank_canceled_cheque_is_received = $request->bank_canceled_cheque_is_received;
			$total_doc++;
			if($request->bank_canceled_cheque_is_received == 'RECEIVED') $total_received++;
		}
		if($request->audited_balance_sheet_is_received != null)
		{
			$merchantDocument->audited_balance_sheet_is_received = $request->audited_balance_sheet_is_received;
			$total_doc++;
			if($request->audited_balance_sheet_is_received == 'RECEIVED') $total_received++;
		}
		if($request->current_account_statement_is_received != null)
		{
			$merchantDocument->current_account_statement_is_received = $request->current_account_statement_is_received;
			$total_doc++;
			if($request->current_account_statement_is_received == 'RECEIVED') $total_received++;
		}
		if($request->income_tax_return_is_received != null)
		{
			$merchantDocument->income_tax_return_is_received = $request->income_tax_return_is_received;
			$total_doc++;
			if($request->income_tax_return_is_received == 'RECEIVED') $total_received++;
		}
		if($request->kartpay_merchant_agreement_is_received != null)
		{
			$merchantDocument->kartpay_merchant_agreement_is_received = $request->kartpay_merchant_agreement_is_received;
			$total_doc++;
			if($request->kartpay_merchant_agreement_is_received == 'RECEIVED') $total_received++;
		}

		//ACTIVATE LIVE DOMAIN
		if($total_doc > $total_received)
		{
				$merchantDocument->courier_id = null;
				$merchantDocument->courier_tracking_id = null;
		}
		$merchantDocument->is_admin_approval == '1';
		$merchantDocument->save();

		//ACTIVATE LIVE DOMAIN
		if($total_doc == $total_received)
		{
			$merchant = Merchant::find($id);
			$merchant->live_domain_active = '1';
			$merchant->save();
		}
		//END ACTIVATE LIVE DOMAIN

		return redirect(route('admins.merchant_administration'));
	}
	/**
		* END Process Merchant Approval Document Step 2
		* @param = merchant id
		*/

	/**
		* Delete Merchant
		* @param = merchant id
		*/
		public function destroy($id)
		{
			AccessKey::where('user_id', $id)->delete();
			$user = Merchant::find($id);
			$userEmail = $user->email;
			$user->delete();

			return redirect(route('admins.merchant_administration'))->with('message', 'Merchant: ' . $userEmail . ' deleted.');
		}
		/**
			* END Delete Merchant
			* @param = merchant id
			*/

	/**
		* Set Status Block Merchant
		* @param = merchant id
		*/
	public function block(Request $request, $id)
	{
		$merchant = Merchant::findOrFail($id);
		cache()->forever($merchant->email.':lockout', 1);

		//flash('User Account is now blocked', 'info');
		return back()->with('message', 'User Account is now blocked');
	}

	/**
		* Set Status Unblock Merchant
		* @param = merchant id
		*/
	public function unblock(Request $request, $id)
	{
		$merchant = Merchant::findOrFail($id);
		app(RateLimiter::class)->clear($merchant->email);

		//flash('User Account is now unblocked', 'info');
		return back()->with('message', 'User Account is now unblocked');
	}
}
