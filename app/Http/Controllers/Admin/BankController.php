<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\Bank;
use App\State;
use Auth;
use Response;

class BankController extends Controller
{
	use LogsActivity;

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Bank Settings';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Bank updated successfuly';
	protected $addSuccess = 'Bank added successfuly';
	protected $destroySuccess = 'Bank destroy successfuly';

	public function __construct()
  {
      $this->middleware('auth:admin');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function bank()
  {
      return view('panel.backend.pages.bank.index');
  }

	/**
   * Display a listing of bank
   *
   * @return array
   */
	public function display()
	{
		$banks = Bank::all();

		$newBanks = array();
		$x = 1;
		foreach($banks as $bank)
		{
			$editlink =  route('admins.edit.bank', ['id' => $bank->id]);
			$deletelink =  route('admins.destroy.bank', ['id' => $bank->id]);

			$actions = '<a href="'.$editlink.'" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Edit bank ' . $bank->bank_name . '"><i class="fa fa-pencil-square-o"></i></a>&nbsp;';

			$actions .= '<button type="button" data-deletelink="'.$deletelink.'" data-name="'.$bank->bank_name.'" class="btn btn-xs btn-danger delete_bank" data-toggle="tooltip" title="Delete bank ' . $bank->bank_name . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$objBanks = array();
			$objBanks[] = $x;
			$objBanks[] = $bank->bank_name;
			$objBanks[] = $bank->bank_code;

			$status = '<select name="bank_status" id="bank_status" class="form-control" onchange="SetBankStatus(this, ' . $bank->id . ');">';
			if($bank->bank_status == 'Active')
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
			$objBanks[] = $status;
			$objBanks[] = $actions;
			$newBanks[] = $objBanks;

			$x++;
		}

		return array( 'data' => $newBanks );
	}
	/**
   * END Display a listing of bank
   *
   */

	 /**
    * Edit Bank
    *
    */
	public function edit($id)
  {
		$bank = Bank::find($id);
		return view('panel.backend.pages.bank.edit', compact('bank'));
  }
	/**
	 * END Edit Bank
	 *
	 */

	 /**
    * Update Bank
    *
    */
	public function update($id, Request $request)
  {
			$user = Auth::guard('admin')->user();
			$bank = Bank::find($id);

			$oldBank               = $bank->bank_name;
	    $validationParam        = '';
	    $rBank               = $request->bank_name;

	    if ($rBank != $bank->bank_name) :
	        $validationParam    = '|unique:banks';
	    endif;

	    $this->validate($request, [
	        'bank_name'           => 'required|max:100'.$validationParam,
	        'bank_status'           => 'required|max:100'
	    ]);

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties([
																			'attributes' =>
																			[
																				'bank_name' => $request->bank_name,
																				'bank_code' => $request->bank_code,
																				'bank_status' => $request->bank_status,

																			],
																			'old' =>
																			[
																				'bank_name' => $bank->bank_name,
																				'bank_code' => $bank->bank_code,
																				'bank_status' => $bank->bank_status,
																			],
																		])->log($this->updateSuccess);
			//END ACTIVITY LOG

			$bank->bank_name = $request->bank_name;
			$bank->bank_code = $request->bank_code;
			$bank->bank_status = $request->bank_status;
			$bank->save();

			return redirect(route('admins.edit.bank', ['id' => $bank->id]))->with(
	                'message',
	                'Bank ' . $bank->bank_name . ' was updated.'
	            );
	}
	/**
	 * END Update Bank
	 *
	 */

	 /**
    * Edit Bank
    *
    */
	public function add()
	{
		return view('panel.backend.pages.bank.add');
	}
	/**
	 * Edit Bank
	 *
	 */

	 /**
    * Store New Bank
    *
    */
	public function store(Request $request)
	{
		$user = Auth::guard('admin')->user();
		$this->validate($request, [
            'bank_name'           => 'required|max:100|unique:banks',
            'bank_status'           => 'required|max:100'
        ]);

		$bank = new Bank();
		$bank->bank_name = $request->bank_name;
		$bank->bank_code = $request->bank_code;
		$bank->bank_status = $request->bank_status;
		$bank->save();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['bank_name' => $request->bank_name, 'bank_code' => $request->bank_code, 'bank_status' => $request->bank_status])->log($this->addSuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.add.bank'))->with(
                'message',
                'Bank ' . $bank->bank_name . ' was added.'
            );
	}
	/**
	 * END Store New Bank
	 *
	 */

	 /**
    * Destroy Existing Bank
    *
    */
	public function destroy($id, Request $request)
	{
		$user = Auth::guard('admin')->user();

		$bank = Bank::find($id);
		$bank->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['bank_name' => $request->bank_name])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return redirect(route('admins.bank'))->with(
                'message',
                'Bank ' . $bank->bank_name . ' and its type was deleted.'
            );
	}
	/**
	 * END Destroy Existing Bank
	 *
	 */

	 /**
    * Set Bank Status (Active/Disabled)
    *
    */
	public function setBankStatus(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$bank = Bank::find($request->id);

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'id' => $bank->id,
																			'bank_status' => $request->bank_status,

																		],
																		'old' =>
																		[
																			'id' => $bank->id,
																			'bank_status' => $bank->bank_status,
																		],
																	])->log($this->updateSuccess);
		//END ACTIVITY LOG

		$bank->bank_status = $request->bank_status;
		$bank->save();

		return Response::json([
								'response' => 'Success',
								'id' => $bank->id,
								'bank_name' => $bank->bank_name,
								'bank_status' => $bank->bank_status
							  ]);
	}
	/**
	 * END Set Bank Status (Active/Disabled)
	 *
	 */
}
