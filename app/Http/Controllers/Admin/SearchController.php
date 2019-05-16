<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\MerchantDocument;
use Carbon\Carbon;
use Auth;
use Response;

class SearchController extends Controller
{
	use LogsActivity;

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	/**
   * Search Courier Tracking ID
   */
	public function search(Request $request)
	{
		if($request->search_option == 'courier_tracking_id')
		{
			$merchantDocument = MerchantDocument::where('courier_tracking_id', '=', $request->search_value)->first();
			if($merchantDocument)
			{
				return Response::json(
				  [
					'response' => 'Success',
					'message' => 'Merchant Document Exist',
					'merchant_id' => $merchantDocument->merchant_id
				  ]
				);
			}
			else
			{
				return Response::json(
				  [
					'response' => 'Error',
					'message' => 'Not Found'
				  ]
				);
			}
		}
	}
	/**
   * END Search Courier Tracking ID
   */
}
