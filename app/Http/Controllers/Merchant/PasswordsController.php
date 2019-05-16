<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Merchant;
use Auth;
use Illuminate\Support\Facades\Session;
use Redirect;
use Hash;

class PasswordsController extends Controller{

	public function create(){
			
		return view('auth.passwords.change');
	}


	public function store(Request $request){

		$merchant = Merchant::where('email', Auth::guard('merchant')->user()->email)->first();
		
		if($merchant){

			if(!Hash::check($request->old_password, $merchant->password)){
				return redirect()->back()->withErrors(['old_password' => 'Old password is incorect']); 
			}
		}

		$this->validate($request, [
			'old_password' => 'required',
			'confirm_password' => 'required|same:new_password|min:8|max:16',
			'new_password'     => 'required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/|last_pass:' . ($merchant->id . '-merchant')
		],  ['new_password.last_pass' => 'New password must not be the same with your recent 5 previous passwords.']);

		

		$merchant->password = bcrypt($merchant->password);
		$saved = $merchant->save();

		if($saved){
			return redirect('/')->with('message', 'Successfully Updated Password');
		}
		
		return redirect()->back()->withErrors(['Error updating password.']); 

	}

}