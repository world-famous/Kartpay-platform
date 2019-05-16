<?php

namespace App\Observers;

use App\Merchant;
use App\PasswordLogs;
use App\Libraries\Passwords;

class MerchantObserver{

	private $key = '-merchant';

	public function saved(Merchant $merchant)
	{
		if($merchant->isDirty('password'))
		{
			Passwords::create([
													'user' => md5($merchant->id . $this->key),
													'password' => $merchant->password
												]);
		}
	}
}
