<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\TermCondition;
use Carbon\Carbon;
use Auth;

class TermConditionController extends Controller
{
	use LogsActivity;
	
	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Terms and Conditions Settings';
	
	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Terms and Conditions updated successfuly';
	
	public function __construct()
    {
        $this->middleware('auth:admin');
    }
	
	public function term()
	{
		$termCondition = TermCondition::find('1');
		return view('panel.backend.pages.term_condition.edit', compact('termCondition'));
	}
	
	public function update(Request $request)
    {
		$user = Auth::guard('admin')->user();
		
		$termCondition = TermCondition::find('1');
		if(!$termCondition) $termCondition = new TermCondition();		
		
		
		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' => 
																		[
																			'message' => $request->message,
																			'content' => $request->content
																			
																		],
																		'old' => 
																		[
																			'message' => $termCondition->message,
																			'content' => $termCondition->content
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG
		
		$termCondition->message = $request->message;
		$termCondition->content = $request->content;
		$termCondition->save();
		
		return redirect(route('admins.term'))->with(
                'message',
                'Terms and Conditions was updated.'
            );
		
		
	}
}
