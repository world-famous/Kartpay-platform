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

class GatewayController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Gateway Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Gateway updated successfuly';
	protected $addSuccess = 'Gateway added successfuly';
	protected $destroySuccess = 'Gateway destroy successfuly';

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

	/**
	 * Load Gateway Page
	 *
	 */
	public function gateway()
	{
		return view('panel.backend.pages.gateway.index');
	}
	/**
	 * END Load Gateway Page
	 *
	 */

	 /**
		* Display a listing of Gateway
		*
		* @return array
		*/
	public function display()
	{
		$gateways = Gateway::all();

		$newGateways = array();
		$x = 1;
		foreach($gateways as $gateway)
		{
			$editlink =  route('admins.edit.gateway', ['id' => $gateway->id]);
			$deletelink =  route('admins.destroy.gateway', ['id' => $gateway->id]);

			$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit gateway ' . $gateway->gateway_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

			$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$gateway->gateway_name.'" class="btn btn-xs btn-danger delete_gateway" data-toggle="tooltip" title="Delete gateway ' . $gateway->gateway_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$gatewayTypeCount = GatewayType::where('gateway_id', $gateway->id)->count();

			$objGateways = array();
			$objGateways[] = $x;
			$objGateways[] = '<a href="' . route('admins.gateway_type', ['id' => $gateway->id]) . '">' . $gateway->gateway_name . ' ' . '(' . $gatewayTypeCount . ')</a>';
			$objGateways[] = $actions;
			$newGateways[] = $objGateways;

			$x++;
		}
		return array( 'data' => $newGateways );
	}
	/**
	 * END Display a listing of Gateway
	 *
	 * @return array
	 */

	 /**
		* Edit Gateway Page
		*
		*/
	public function edit($id)
  {
		$gateway = Gateway::find($id);
		return view('panel.backend.pages.gateway.edit', compact('gateway'));
  }
	/**
	 * END Edit Gateway Page
	 *
	 */

	 /**
	 * Update Gateway
	 *
	 */
	public function update($id, Request $request)
  {
		$user = Auth::guard('admin')->user();
		$gateway = Gateway::find($id);

		$oldGateway               = $gateway->gateway_name;
	      $validationParam        = '';
	      $rGateway               = $request->gateway_name;

	      if ($rGateway != $gateway->gateway_name) :
	          $validationParam    = '|unique:gateways';
	      endif;

	      $this->validate($request, [
	          'gateway_name'           => 'required|max:100'.$validationParam
	      ]);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'gateway_name' => $request->gateway_name,

																		],
																		'old' =>
																		[
																			'gateway_name' => $gateway->gateway_name,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$gateway->gateway_name = $request->gateway_name;
		$gateway->save();

		return redirect(route('admins.edit.gateway', ['id' => $gateway->id]))->with(
                'message',
                'Gateway ' . $gateway->gateway_name . ' was updated.'
            );
	}
	/**
	* END Update Gateway
	*
	*/

	/**
	* Add Gateway
	*
	*/
	public function add()
	{
		return view('panel.backend.pages.gateway.add');
	}
	/**
	* END Add Gateway
	*
	*/

	/**
	* Store New Gateway
	*
	*/
	public function store(Request $request)
	{
		$user = Auth::guard('admin')->user();
		$this->validate($request, [
            'gateway_name'           => 'required|max:100|unique:gateways'
        ]);

		$gateway = new Gateway();
		$gateway->gateway_name = $request->gateway_name;
		$gateway->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['gateway_name' => $request->gateway_name])->log($this->addSuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.add.gateway'))->with(
                'message',
                'Gateway ' . $gateway->gateway_name . ' was added.'
            );
	}
	/**
	* END Store New Gateway
	*
	*/

	/**
	* Destroy Existing Gateway
	*
	*/
	public function destroy($id, Request $request)
	{
		$user = Auth::guard('admin')->user();

		$gateway = Gateway::find($id);
		$gateway->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['gateway_name' => $request->gateway_name])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.gateway'))->with(
                'message',
                'Gateway ' . $gateway->gateway_name . ' and its type was deleted.'
            );
	}
	/**
	* END Destroy Existing Gateway
	*
	*/
}
