<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Illuminate\Support\Facades\Session;
use Redirect;
use Hash;

class PasswordsController extends Controller{

	/**
		* Load Change Password Page
		*
		*/
	public function create()
	{
		return view('auth.passwords.change');
	}
	/**
		* END Load Change Password Page
		*
		*/

	/**
		* Store Changed Password
		*
		*/
	public function store(Request $request)
	{
		$user = user::where('email', Auth::guard('admin')->user()->email)->first();

		if($user)
		{
			if(!Hash::check($request->old_password, $user->password))
			{
				return redirect()->back()->withErrors(['old_password' => 'Old password is incorect']);
			}
		}

		$this->validate($request, [
			'old_password' => 'required',
			'confirm_password' => 'required|same:new_password|min:8|max:16',
			'new_password'     => 'required|min:8|max:16|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!?])(?=.*[0-9]).*$/|last_pass:' . ($user->id . '-user')
															],
															['new_password.last_pass' => 'New password must not be the same with your recent 5 previous passwords.'
															]
										);

		$user->password = bcrypt($user->password);
		$saved = $user->save();

		if($saved)
		{
			return redirect('/')->with('message', 'Successfully Updated Password');
		}
		return redirect()->back()->withErrors(['Error updating password.']);
	}
	/**
		* END Store Changed Password
		*
		*/
}
