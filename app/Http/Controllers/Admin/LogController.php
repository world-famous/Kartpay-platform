<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use App\User;
use App\Merchant;

class LogController extends Controller
{

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	public function log()
	{
		return view('panel.backend.pages.log.user_log');
	}

	/**
	 * Display a listing of Log
	 *
	 * @return array
	 */
	public function display()
	{
		$activitys = Activity::all();

		$newActivity = array();
		foreach($activitys as $activity)
		{
			$objActivity = array();
			$objActivity[] = $activity->log_name;
			$objActivity[] = $activity->description;

			if($activity->causer_type == 'App\User') $user = User::find($activity->causer_id);
			if($activity->causer_type == 'App\Merchant') $user = Merchant::find($activity->causer_id);
			if(isset($user->first_name))
				$objActivity[] = $user->first_name . ' ' . $user->last_name;
			else $objActivity[] = '<span class="label label-danger">User not found</span>';

			$causerType = 'Merchant';
			if($activity->causer_type == 'App\User') $causerType = 'Admin';
			$objActivity[] = $causerType;
			$objActivity[] = date('M j Y g:i A', strtotime($activity->created_at));
			$newActivity[] = $objActivity;
		}
		return array( 'data' => $newActivity );
	}
	/**
	 * END Display a listing of Log
	 *
	 * @return array
	 */
}
