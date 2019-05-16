<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Country;
use App\State;
use App\City;
use Carbon\Carbon;
use Auth;
use Response;

class StateController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'State Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'State updated successfuly';
	protected $addSuccess = 'State added successfuly';
	protected $destroySuccess = 'State destroy successfuly';

	public function __construct()
    {
        $this->middleware('auth:admin');
    }

	public function state()
	{
		return view('panel.backend.pages.state.index');
	}

	public function display()
	{
		$states = State::join('countrys', 'states.country_id', '=', 'countrys.id')
						->select('states.id', 'states.state_name', 'states.state_code', 'states.state_status', 'states.country_id', 'countrys.country_code')
						->get();

		$newStates = array();
		$x = 1;
		foreach($states as $state)
		{
			$editlink =  route('admins.edit.state', ['pid' => $state->id]);
			$deletelink =  route('admins.destroy.state', ['pid' => $state->id]);

			$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit state ' . $state->state_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

			$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$state->state_name.'" class="btn btn-xs btn-danger delete_state" data-toggle="tooltip" title="Delete state ' . $state->state_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$cityCount = City::where('state_id', $state->id)->count();

			$objStates = array();
			$objStates[] = $x;
			$objStates[] = $state->state_name;
			$objStates[] = $state->state_code;
			$objStates[] = $state->country_code;

			$status = '<select name="state_status" id="state_status" class="form-control" onchange="SetStateStatus(this, ' . $state->id . ');">';
			if($state->state_status == 'Active')
			{
				$status .= '<option value="Active" selected>Active</option>';
				$status .= '<option value="Disabled">Disabled</option>';
			}
			else
			{
				$status .= '<option value="Active">Active</option>';
				$status .= '<option value="Disabled" selected>Disabled</option>';
			}
			$status .= '</select>';
			$objStates[] = $status;
			$objStates[] = $actions;
			$newStates[] = $objStates;

			$x++;
		}

		return array( 'data' => $newStates );
	}
	
	public function edit($pid)
    {
		$countrys = Country::all();
		$state = State::find($pid);
		return view('panel.backend.pages.state.edit', compact('countrys', 'state'));
    }

	public function update($pid, Request $request)
    {
		$user = Auth::guard('admin')->user();

		$state = State::find($pid);

		$oldState               	= $state->state_name;
        $validationParam        		= '';
        $rState               	= $request->state_name;

        if ($rState != $state->state_name) :
            $validationParam    = '|unique:states';
        endif;

        $this->validate($request, [
            'state_name'           => 'required|max:100'.$validationParam,
            'state_code'           => 'required|max:100',
            'state_status'           => 'required|max:100',
            'country_id'           => 'required|max:100'
        ]);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'state_name' => $request->state_name,
																			'state_code' => $request->state_code,
																			'state_status' => $request->state_status,
																			'country_id' => $request->country_id,

																		],
																		'old' =>
																		[
																			'state_name' => $state->state_name,
																			'state_code' => $state->state_code,
																			'state_status' => $state->state_status,
																			'country_id' => $state->country_id,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$state->state_name = $request->state_name;
		$state->state_code = $request->state_code;
		$state->state_status = $request->state_status;
		$state->country_id = $request->country_id;
		$state->save();

		return redirect(route('admins.edit.state', ['pid' => $state->id]))->with(
                'message',
                'State ' . $state->state_name . ' was updated.'
            );


	}

	public function add()
	{
		$countrys = Country::all();
		return view('panel.backend.pages.state.add', compact('countrys'));
	}

	public function store(Request $request)
	{
		$user = Auth::guard('admin')->user();
		$this->validate($request, [
			'country_id'		   => 'required',
            'state_name'           => 'required|max:100|unique:states',
			'state_code'	   	   => 'required',
			'state_status'	   	   => 'required'
        ]);

		$state = new State();
		$state->country_id = $request->country_id;
		$state->state_name = $request->state_name;
		$state->state_code = $request->state_code;
		$state->state_status = $request->state_status;
		$state->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['country_id' => $request->country_id, 'state_name' => $request->state_name, 'state_code' => $request->state_code, 'state_status' => $request->state_status])->log($this->addSuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.add.state'))->with(
                'message',
                'State ' . $state->state_name . ' was added.'
            );
	}

	public function destroy($pid, Request $request)
	{
		$user = Auth::guard('admin')->user();

		$state = State::find($pid);
		$state->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['state_name' => $request->state_name])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.state'))->with(
                'message',
                'State ' . $state->state_name . ' was deleted.'
            );
	}

	public function setStateStatus(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$state = State::find($request->id);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'id' => $state->id,
																			'state_status' => $request->state_status,

																		],
																		'old' =>
																		[
																			'id' => $state->id,
																			'state_status' => $state->state_status,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$state->state_status = $request->state_status;
		$state->save();

		return Response::json([
								'response' => 'Success',
								'id' => $state->id,
								'state_name' => $state->state_name,
								'state_status' => $state->state_status
							  ]);
	}

	function getStateByCountry(Request $request)
	{
		$country = Country::where('id', $request->country_id)->first();
		$responseStates = '';
		if(isset($country->states)) $responseStates = $country->states;

		return Response::json(
		  [
			'response' => 'Success',
			'states' => $responseStates
		  ]
		);
	}
}
