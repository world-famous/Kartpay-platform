<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Country;
use App\State;
use Auth;
use Response;

class CountryController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Country Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Country updated successfuly';
	protected $addSuccess = 'Country added successfuly';
	protected $destroySuccess = 'Country destroy successfuly';

	public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function country()
    {
        return view('panel.backend.pages.country.index');
    }

		/**
     * Display a listing of Country
     *
     * @return array
     */
		public function display()
		{
			$countrys = Country::all();

			$newCountrys = array();
			$x = 1;
			foreach($countrys as $country)
			{
				$editlink =  route('admins.edit.country', ['id' => $country->id]);
				$deletelink =  route('admins.destroy.country', ['id' => $country->id]);

				$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit country ' . $country->country_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

				$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$country->country_name.'" class="btn btn-xs btn-danger delete_country" data-toggle="tooltip" title="Delete country ' . $country->country_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

				$stateCount = State::where('country_id', $country->id)->count();

				$objCountrys = array();
				$objCountrys[] = $x;
				$objCountrys[] = $country->country_name;
				$objCountrys[] = $country->country_code;

				$status = '<select name="country_status" id="country_status" class="form-control" onchange="SetCountryStatus(this, ' . $country->id . ');">';
				if($country->country_status == 'Active')
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
				$objCountrys[] = $status;
				$objCountrys[] = $actions;
				$newCountrys[] = $objCountrys;

				$x++;
			}
			return array( 'data' => $newCountrys );
		}
		/**
     * Display a listing of Country
     *
     */

		 /**
	    * Edit Country Page
	    *
	    */
			public function edit($id)
	    {
				$country = Country::find($id);
				return view('panel.backend.pages.country.edit', compact('country'));
	    }
			/**
 	    * END Edit Country Page
 	    *
 	    */

			/**
 	    * Update Country
 	    *
 	    */
			public function update($id, Request $request)
	    {
				$user = Auth::guard('admin')->user();
				$country = Country::find($id);

				$oldCountry               = $country->country_name;
	      $validationParam        = '';
	      $rCountry               = $request->country_name;

	      if ($rCountry != $country->country_name) :
	          $validationParam    = '|unique:countrys';
	      endif;

	      $this->validate($request, [
	          'country_name'           => 'required|max:100'.$validationParam,
	          'country_code'           => 'required|max:100',
	          'country_status'           => 'required|max:100'
	      ]);

				//ACTIVITY LOG
				activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'country_name' => $request->country_name,
																				'country_code' => $request->country_code,
																				'country_status' => $request->country_status,

																			],
																			'old' =>
																			[
																				'country_name' => $country->country_name,
																				'country_code' => $country->country_code,
																				'country_status' => $country->country_status,
																			],
																		])->log($this->updateSuccess);
				//END ACTIVITY LOG

				$country->country_name = $request->country_name;
				$country->country_code = $request->country_code;
				$country->country_status = $request->country_status;
				$country->save();

				return redirect(route('admins.edit.country', ['id' => $country->id]))->with(
		                'message',
		                'Country ' . $country->country_name . ' was updated.'
		            );
		}
		/**
		 * END Update Country
		 *
		 */

		 /**
		 * Add Country
		 *
		 */
		public function add()
		{
			return view('panel.backend.pages.country.add');
		}
		/**
		* END Add Country
		*
		*/

		/**
		* Store New Country
		*
		*/
		public function store(Request $request)
		{
			$user = Auth::guard('admin')->user();
			$this->validate($request, [
	            'country_name'           => 'required|max:100|unique:countrys',
	            'country_code'           => 'required|max:100|unique:countrys',
	            'country_status'           => 'required|max:100'
	        ]);

			$country = new Country();
			$country->country_name = $request->country_name;
			$country->country_code = $request->country_code;
			$country->country_status = $request->country_status;
			$country->save();

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties(['country_name' => $request->country_name, 'country_code' => $request->country_code, 'country_status' => $request->country_status])->log($this->addSuccess);
			//END ACTIVITY LOG

			return redirect(route('admins.add.country'))->with(
	                'message',
	                'Country ' . $country->country_name . ' was added.'
	            );
		}
		/**
		* END  New Country
		*
		*/

		/**
		* Destroy Existing Country
		*
		*/
		public function destroy($id, Request $request)
		{
			$user = Auth::guard('admin')->user();

			$country = Country::find($id);
			$country->delete();

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties(['country_name' => $request->country_name])->log($this->destroySuccess);
			//END ACTIVITY LOG

			return redirect(route('admins.country'))->with(
	                'message',
	                'Country ' . $country->country_name . ' and its type was deleted.'
	            );
		}
		/**
		* END Destroy Existing Country
		*
		*/

		/**
		* Set Country Status (Active/Disabled)
		*
		*/
		public function setCountryStatus(Request $request)
		{
			$user = Auth::guard('admin')->user();

			$country = Country::find($request->id);

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'id' => $country->id,
																				'country_status' => $request->country_status,

																			],
																			'old' =>
																			[
																				'id' => $country->id,
																				'country_status' => $country->country_status,
																			],
																		])->log($this->updateSuccess);
			//END ACTIVITY LOG

			$country->country_status = $request->country_status;
			$country->save();

			return Response::json([
									'response' => 'Success',
									'id' => $country->id,
									'country_name' => $country->country_name,
									'country_status' => $country->country_status
								  ]);
		}
		/**
		* END Set Country Status (Active/Disabled)
		*
		*/

}
