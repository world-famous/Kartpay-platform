<?php

namespace App\Observers;

use App\User;
use App\PasswordLogs;
use App\Libraries\Passwords;

class UserObserver{

	private $key = '-admin';

	public function saved(User $user)
	{
		if($user->isDirty('password'))
		{
			Passwords::create([
													'user' => md5($user->id . $this->key),
													'password' => $user->password
												]);
		}
	}
}
