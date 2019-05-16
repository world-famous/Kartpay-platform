<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;
use App\Merchant;
use Auth;

class LogController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth:merchant');
    }
	
	public function log()
	{
		return view('merchant.backend.pages.log.user_log');
	}
	
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
}
