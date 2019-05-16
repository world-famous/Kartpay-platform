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

class CityController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'City Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'City updated successfuly';
	protected $addSuccess = 'City added successfuly';
	protected $destroySuccess = 'City destroy successfuly';

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	/**
	 * Load City Page
	 *
	 */
	public function city()
	{
		return view('panel.backend.pages.city.index');
	}
	/**
	 * END Load City Page
	 *
	 */

	 /**
    * Display a listing of City
    *
    * @return array
    */
	public function display()
	{
		$citys = City::join('states', 'citys.state_id', '=', 'states.id')
						->join('countrys', 'states.country_id', '=', 'countrys.id')
						->select('citys.id as city_id', 'citys.city_name', 'citys.city_code', 'citys.city_status', 'states.id as state_id', 'states.country_id', 'states.state_code', 'countrys.country_name', 'countrys.country_code')
						->get();

		$newCitys = array();
		$x = 1;
		foreach($citys as $city)
		{
			$editlink =  route('admins.edit.city', ['cid' => $city->city_id]);
			$deletelink =  route('admins.destroy.city', ['cid' => $city->city_id]);

			$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit city ' . $city->city_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

			$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$city->city_name.'" class="btn btn-xs btn-danger delete_city" data-toggle="tooltip" title="Delete city ' . $city->city_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$objCitys = array();
			$objCitys[] = $x;
			$objCitys[] = $city->city_name;
			//$objCitys[] = $city->city_code;
			$objCitys[] = $city->state_code;
			$objCitys[] = $city->country_code;

			$status = '<select name="city_status" id="city_status" class="form-control" onchange="SetCityStatus(this, ' . $city->city_id . ');">';
			if($city->city_status == 'Active')
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
			$objCitys[] = $status;
			$objCitys[] = $actions;
			$newCitys[] = $objCitys;

			$x++;
		}
		return array( 'data' => $newCitys );
	}
	/**
   * Display a listing of city
   *
   */

	 /**
    * Edit City
    *
    */
	public function edit($cid)
  {
		$countrys = Country::all();

		$city = City::find($cid);
		$tempState = State::where('id', $city->state_id)->first();

		$states = State::where('country_id', $tempState->country_id)->get();
		return view('panel.backend.pages.city.edit', compact('countrys', 'states', 'tempState', 'city'));
  }
	/**
	 * END Edit City
	 *
	 */

	 /**
 	 * Update City
 	 *
 	 */
	public function update($cid, Request $request)
	{
		$user = Auth::guard('admin')->user();
		$city = City::find($cid);

		$oldCity               	= $city->city_name;
	      $validationParam        		= '';
	      $rCity               	= $request->city_name;

	      if ($rCity != $city->city_name) :
	          $validationParam    = '|unique:citys';
	      endif;

	      $this->validate($request, [
	          'city_name'           => 'required|max:100'.$validationParam,
			'country_id'				  => 'required',
			'state_id'				  => 'required',
			//'city_code'				  => 'required',
			'city_status'				  => 'required'
	      ]);

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'city_name' => $request->city_name,
																			'state_id' => $request->state_id,
																			'city_code' => $request->city_code,
																			'city_status' => $request->city_status,
																		],
																		'old' =>
																		[
																			'city_name' => $city->city_name,
																			'state_id' => $city->state_id,
																			'city_code' => $city->city_code,
																			'city_status' => $city->city_status,
																		],
																	])->log($this->updateSuccess);
			//END ACTIVITY LOG

			$city->city_name = $request->city_name;
			$city->state_id = $request->state_id;
			$city->city_code = $request->city_code;
			$city->city_status = $request->city_status;
			$city->save();

			return redirect(route('admins.edit.city',['cid' => $city->id]))->with(
	                'message',
	                'City ' . $city->city_name . ' was updated.'
	            );
	}
	/**
	 * END Update Country
	 *
	 */

	 /**
 	 * Display Add New Country Page
 	 *
 	 */
	public function add()
	{
		$countrys = Country::all();
		return view('panel.backend.pages.city.add', compact('countrys'));
	}
	/**
	* END Display Add New Country Page
	*
	*/

	/**
	* Store New City
	*
	*/
	public function store(Request $request)
	{
		$user = Auth::guard('admin')->user();
		$this->validate($request, [
			'country_id'				  => 'required',
			'state_id'				  => 'required',
            'city_name'           => 'required|max:100|unique:citys',
			//'city_code'				  => 'required',
			'city_status'				  => 'required'
        ]);

		$city = new City();
		$city->city_name = $request->city_name;
		$city->city_code = $request->city_code;
		$city->city_status = $request->city_status;
		$city->state_id = $request->state_id;
		$city->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['city_name' => $request->city_name, 'city_code' => $request->city_code, 'city_status' => $request->city_status, 'state_id' => $request->state_id])->log($this->addSuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.add.city'))->with(
                'message',
                'City ' . $city->city_name . ' was added.'
            );
	}
	/**
	* END Store New City
	*
	*/

	/**
	* Destroy Existing City
	*
	*/
	public function destroy($cid, Request $request)
	{
		$user = Auth::guard('admin')->user();

		$city = City::find($cid);
		$city->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['city_name' => $request->city_name])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.city'))->with(
                'message',
                'City ' . $city->city_name . ' was deleted.'
            );
	}
	/**
	* END Destroy Existing City
	*
	*/

	/**
	* Set City Status
	*
	*/
	public function setCityStatus(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$city = City::find($request->id);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'id' => $request->id,
																			'city_status' => $request->city_status,

																		],
																		'old' =>
																		[
																			'id' => $city->id,
																			'city_status' => $city->city_status,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$city->city_status = $request->city_status;
		$city->save();

		return Response::json([
								'response' => 'Success',
								'id' => $city->id,
								'city_name' => $city->city_name,
								'city_status' => $city->city_status
							  ]);
	}
	/**
	* SEND Set City Status
	*
	*/
}
