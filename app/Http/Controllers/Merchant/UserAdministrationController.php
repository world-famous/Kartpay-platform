<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Merchant;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Merchant\Auth\RegisterController;

class UserAdministrationController extends RegisterController
{
	use LogsActivity;
	
	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'User Administration';
	
	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'User updated successfuly';
	
	public function __construct()
    {
        $this->middleware('auth:merchant');
    }
	
	public function userAdministration()
	{
		$user = Auth::guard('merchant')->user();
		return view('merchant.backend.pages.user.user_administration', compact('user'));
	}
	
	public function display()
	{
		$users = Merchant::where('merchant_id', '=', Auth::guard('merchant')->user()->id)->get();
		
		$newUsers = array();
		$x = 1;
		foreach($users as $user)
		{
			$viewlink =  url('/user_administration/' . $user->id . '/show');
			$editlink =  url('/user_administration/' . $user->id . '/edit');
			$invitationlink =  route('merchants.send_email_invitation', ['id' => $user->id]);
			
			$actions = '<a href="'.$viewlink.'" class="btn btn-xs btn-success" data-toggle="tooltip" title="View user ' . $user->first_name . ' ' . $user->last_name . '"><i class="fa fa-eye"></i></a>&nbsp;';
			$actions .= '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit user ' . $user->first_name . ' ' . $user->last_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';
			
			if($user->is_active == '0')
			{
				$actions .= '<a href="'.$invitationlink.'" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Send Invitation Registration Staff ' . $user->email . '"><i class="fa fa-envelope"></i></a>&nbsp;';
			}
			
			$objUsers = array();		
			$objUsers[] = $x;
			$objUsers[] = $user->first_name;	
			$objUsers[] = $user->last_name;
			$objUsers[] = $user->country_code . ' ' . $user->contact_no;
			$objUsers[] = $user->email;
			
			$type = 'merchant';
			if($user->type == 'staff') $type = 'Staff';
			$objUsers[] = $type;
			
			$status = '<span class="label label-success">Active</span>';
			if($user->is_active == '0') $status = '<span class="label label-danger">Not Active</span>';
			$objUsers[] = $status;
			$objUsers[] = $actions;
			$newUsers[] = $objUsers;
			
			$x++;
		}
		
		return array( 'data' => $newUsers );
	}
	
	public function show($id)
    {
		$user = Merchant::find($id);
		return view('merchant.backend.pages.user.user_administration_show', compact('user'));
    }
	
	public function edit($id)
    {
		$user = Merchant::find($id);
		return view('merchant.backend.pages.user.user_administration_edit', compact('user'));
    }
	
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
		$user->save();
		
		
		
		return redirect('/user_administration/' . $user->id . '/edit')->with(
                'message',
                'User <a href="'.url('user_administration/' . $user->id . '/show').'">'.$user->first_name .' ' .$user->last_name . ' ('.$user->email.')</a> was updated.'
            );
		
		
	}
	
	public function addNewStaff()
	{
		return view('merchant.backend.pages.user.user_administration_add_staff');
	}
	
	public function storeNewStaff(Request $request)
	{
		$this->validate($request, [
            'email'           => 'email|required|max:100|unique:merchants'
        ]);
		
		$merchant = Auth::guard('merchant')->user();
		
		$user = new Merchant();
		$user->password = '';
		$user->first_name = '';
		$user->last_name = '';
		$user->country_code = '';
		$user->contact_no = '';
		$user->verification_code = $this->checkUniqueVerificationCode();
		$user->last_send_email_secret_login = Carbon::now();
		$user->otp = $this->checkUniqueOtp();
		$user->allow_login = '0';
		
		$user->email = $request->email;
		$user->type = 'staff';
		$user->merchant_id = $merchant->id;
		$user->save();
		
		return redirect('/user_administration/add_new_staff')->with(
                'message',
                'Staff with email <b>' . $user->email . '</b> was added. <a class="btn btn-warning" href="' . route('merchants.send_email_invitation', ['id' => $user->id]) . '">Send Email Invitation</a>'
            );
	}
	
	public function sendEmailInvitation($id)
	{
		$user = Merchant::find($id);		
		$user->verification_code = $this->checkUniqueVerificationCode();
		$user->last_send_email_secret_login = Carbon::now();
		$user->save();
		
		//Send Email Invitation
			$this->send_verification_email($user->name, $user->email, $user->name, '(Registration) Link for staff registration from Kartpay', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), route('merchants.staff_registration', ['id' => $user->verification_code]), 'Here is the link to complete the registration:');
		//End Send Email Invitation
		
		return redirect('/user_administration')->with(
                'message',
                'Invitation email to <b>' . $user->email . '</b> sent.'
            );
	}	
}
