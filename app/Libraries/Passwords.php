<?php

namespace App\Libraries;

use App\PasswordLogs;
use Carbon\Carbon;

class Passwords {

	private static $maxDays = 90;

	public static function create($data)
	{
		return PasswordLogs::create($data);
	}

	public static function requireChange($id, $guard)
	{
		$logs = PasswordLogs::where('user', md5($id . '-' . $guard))->orderBy('id', 'DESC')->first();

		if(!$logs)
		{
			return false;
		}

		$now = Carbon::now();
		$last_change = new Carbon($logs->created_at);
		$difference = $last_change->diff($now)->days;

		if($difference >= self::$maxDays)
		{
			return true;
		}
		return false;
	}
}
