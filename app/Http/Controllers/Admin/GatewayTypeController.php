<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Gateway;
use App\GatewayType;
use Carbon\Carbon;
use Auth;

class GatewayTypeController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Gateway Type Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Gateway Type updated successfuly';
	protected $addSuccess = 'Gateway Type added successfuly';
	protected $destroySuccess = 'Gateway Type destroy successfuly';

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	/**
	 * Load Gateway Type Page
	 *
	 */
	public function gatewayType($id)
	{
		$gateway = Gateway::find($id);
		return view('panel.backend.pages.gateway_type.index', compact('gateway'));
	}
	/**
	 * END Load Gateway Type Page
	 *
	 */

	 /**
		* Display a listing of Gateway Type
		*
		* @return array
		*/
	public function display($id)
	{
		$gateway = Gateway::find($id);
		$gatewayTypes = GatewayType::where('gateway_id', $id)->get();

		$newGatewayTypes = array();
		$x = 1;
		foreach($gatewayTypes as $gatewayType)
		{
			$editlink =  route('admins.edit.gateway_type', ['id' => $gateway->id, 'pid' => $gatewayType->id]);
			$deletelink =  route('admins.destroy.gateway_type', ['id' => $gateway->id, 'pid' => $gatewayType->id]);

			$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit gateway type ' . $gatewayType->gateway_type_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

			$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$gatewayType->gateway_type_name.'" class="btn btn-xs btn-danger delete_gateway" data-toggle="tooltip" title="Delete gateway type ' . $gatewayType->gateway_type_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$objGatewayTypes = array();
			$objGatewayTypes[] = $x;
			$objGatewayTypes[] = $gatewayType->gateway_type_name;

			$is_enable = 'ENABLE';
			if($gatewayType->is_enable == '0') $is_enable = 'DISABLE';
			$objGatewayTypes[] = $is_enable;
			$objGatewayTypes[] = $gatewayType->route;
			$objGatewayTypes[] = $actions;
			$newGatewayTypes[] = $objGatewayTypes;

			$x++;
		}
		return array( 'data' => $newGatewayTypes );
	}
	/**
	 * END Display a listing of Gateway Type
	 *
	 * @return array
	 */

	 /**
		* Edit Gateway Type Page
		*
		*/
	public function edit($id, $pid)
  {
		$gateway = Gateway::find($id);
		$gatewayType = GatewayType::find($pid);
		return view('panel.backend.pages.gateway_type.edit', compact('gateway', 'gatewayType'));
  }
	/**
	 * END Edit Gateway Type Page
	 *
	 */

	 /**
	 * Update Gateway Type
	 *
	 */
	public function update($id, $pid, Request $request)
  {
		$user = Auth::guard('admin')->user();
		$gateway = Gateway::find($id);
		$gatewayType = GatewayType::find($pid);

		$oldGatewayType               	= $gatewayType->gateway_type_name;
        $validationParam        		= '';
        $rGatewayType               	= $request->gateway_type_name;

        if ($rGatewayType != $gatewayType->gateway_type_name) :
            $validationParam    = '|unique:gateway_types';
        endif;

        $this->validate($request, [
            'gateway_type_name'           => 'required|max:100'.$validationParam,
			'is_enable'					  => 'required',
			'route'					      => 'url'
        ]);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'gateway_type_name' => $request->gateway_type_name,

																		],
																		'old' =>
																		[
																			'gateway_type_name' => $gateway->gateway_type_name,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$gateway->gateway_type_name = $request->gateway_type_name;
		$gateway->save();

		return redirect(route('admins.edit.gateway_type', ['id' => $gateway->id, 'pid' => $gatewayType->id]))->with(
                'message',
                'Gateway Type ' . $gatewayType->gateway_type_name . ' was updated.'
            );
	}
	/**
	* END Update Gateway Type
	*
	*/

	/**
	* Add Gateway Type
	*
	*/
	public function add($id)
	{
		$gateway = Gateway::find($id);
		return view('panel.backend.pages.gateway_type.add', compact('gateway'));
	}
	/**
	* END Add Gateway Type
	*
	*/

	/**
	* Store New Gateway
	*
	*/
	public function store($id, Request $request)
	{
		$gateway = Gateway::find($id);
		$user = Auth::guard('admin')->user();
		$this->validate($request, [
            'gateway_type_name'           => 'required|max:100|unique:gateway_types',
			'is_enable'					  => 'required',
			'route'					      => 'url',
			'gateway_id'				  => 'required'
        ]);

		$gatewayType = new GatewayType();
		$gatewayType->gateway_type_name = $request->gateway_type_name;
		$gatewayType->is_enable = $request->is_enable;
		$gatewayType->route = $request->route;
		$gatewayType->gateway_id = $request->gateway_id;
		$gatewayType->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['gateway_type_name' => $request->gateway_type_name])->log($this->addSuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.add.gateway_type', ['id' => $gateway->id, 'pid' => $gatewayType->id]))->with(
                'message',
                'Gateway Type ' . $gatewayType->gateway_type_name . ' was added.'
            );
	}
	/**
	* Store New Gateway Type
	*
	*/

	/**
	* Destroy Existing Gateway Type
	*
	*/
	public function destroy($id, $pid, Request $request)
	{
		$gateway = Gateway::find($id);
		$user = Auth::guard('admin')->user();

		$gatewayType = GatewayType::find($pid);
		$gatewayType->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['gateway_type_name' => $request->gateway_type_name])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.gateway_type', ['id' => $gateway->id]))->with(
                'message',
                'Gateway Type ' . $gatewayType->gateway_type_name . ' was deleted.'
            );
	}
	/**
	* END Destroy Existing Gateway Type
	*
	*/
}
