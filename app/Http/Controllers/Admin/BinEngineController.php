<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use App\BinEngine;
use App\State;
use Auth;
use Response;

class BinEngineController extends Controller
{
	use LogsActivity;

	/**
   * Log Event
   *
   * @var string
   */
	protected $eventUpdate = 'Bin Engine';
	/**
		* End Log eventUpdate
		*/

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Bin Engine updated successfuly';
	protected $addSuccess = 'Bin Engine added successfuly';
	protected $destroySuccess = 'Bin Engine destroy successfuly';
	/**
		* End Log Description
		*/

	public function __construct()
  {
        $this->middleware('auth:admin');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function binEngine()
  {
			$bin_engines = BinEngine::all();
      return view('panel.backend.pages.bin_engine.index', compact('bin_engines'));
  }
	/**
		* End Display a listing of the resource.
		*/

	/**
		* Search by BIN Number
		*/
	public function search(Request $request)
  {
			$bin_engines = BinEngine::where($request->field, 'like', '%' . $request->search . '%')->get();

			$newBinEngines = array();
			$x = 1;
			foreach($bin_engines as $bin_engine)
			{
				$actions = '<button type="button" data-id="'.$bin_engine->id.'" data-name="'.$bin_engine->bin_number.'" class="btn btn-xs btn-danger delete_bin_engine" data-toggle="tooltip" title="Delete Bin Number ' . $bin_engine->bin_number . '"><i class="fa fa-trash"></i></button>&nbsp;';

				$objBinEngines = array();
				$objBinEngines[] = $x;
				$objBinEngines[] = $bin_engine->bin_number;
				$objBinEngines[] = $bin_engine->card_issuer;
				$objBinEngines[] = $bin_engine->card_type;
				$objBinEngines[] = $bin_engine->bank_name;
				$objBinEngines[] = $bin_engine->country_name;
				$objBinEngines[] = $actions;
				$newBinEngines[] = $objBinEngines;

				$x++;
			}

			return array( 'data' => $newBinEngines );
  }
	/**
		* END Search by BIN Number
		*/

	/**
	 * Display a listing of the bin engine data.
	 */
	public function display()
	{
		$bin_engines = BinEngine::all();

		$newBinEngines = array();
		$x = 1;
		foreach($bin_engines as $bin_engine)
		{
			$actions = '<button type="button" data-id="'.$bin_engine->id.'" data-name="'.$bin_engine->bin_number.'" class="btn btn-xs btn-danger delete_bin_engine" data-toggle="tooltip" title="Delete Bin Number ' . $bin_engine->bin_number . '"><i class="fa fa-trash"></i></button>&nbsp;';

			$objBinEngines = array();
			$objBinEngines[] = $x;
			$objBinEngines[] = $bin_engine->bin_number;
			$objBinEngines[] = $bin_engine->card_issuer;
			$objBinEngines[] = $bin_engine->card_type;
			$objBinEngines[] = $bin_engine->bank_name;
			$objBinEngines[] = $bin_engine->country_name;
			$objBinEngines[] = $actions;
			$newBinEngines[] = $objBinEngines;

			$x++;
		}

		return array( 'data' => $newBinEngines );
	}
	/**
	 * End Display a listing of the bin engine data.
	 */

 /**
	 * Store new bin engine data.
	 * Table: bin_engines.
	 * Input: bin_number,
	 */
	public function storeNewBin(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$oldBin = BinEngine::where('bin_number', $request->bin_number)->first();
		if($oldBin)
		{
			return Response::json(
				[
					'response' => 'error',
					'message' => 'Bin Number already exist'
				]
			);
		}
		else
		{
			//CHECKING bincode.com if bin code is exist then get data
			$url = 'https://api.bincodes.com/bin/?format=' . config('bin')['bin_codes_format'] . '&api_key=' . config('bin')['bin_codes_api_key'] . '&bin=' . $request->bin_number;

	    $ch = curl_init();

	    curl_setopt($ch, CURLOPT_URL, $url); //Url together with parameters
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Return data instead printing directly in Browser
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 7); //Timeout after 7 seconds
	    curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
	    curl_setopt($ch, CURLOPT_HEADER, 0);

	    $result = curl_exec($ch);
			$error = curl_error($ch);
	    curl_close($ch);

			if($error)  //catch if curl error exists and show it
			{
					return Response::json(
					[
						'response' => 'error',
						'message' => 'Curl error: ' . $error
					]
				);
			}
			else
			{
			  $resultArr = json_decode($result, true);

				if(!empty($resultArr['message']))
				{
						return Response::json(
						[
							'response' => 'error',
							'message' => $resultArr['message']
						]
					);
				}
				else
				{
					$bin_engine = new BinEngine();
					$bin_engine->bin_number = $resultArr['bin'];
					$bin_engine->card_issuer = $resultArr['card'];
					$bin_engine->card_type = $resultArr['type'];
					$bin_engine->bank_name = $resultArr['bank'];
					$bin_engine->country_name = $resultArr['country'];
					$bin_engine->country_code = $resultArr['countrycode'];
					$bin_engine->save();

					//ACTIVITY LOG
					activity($this->eventUpdate)->causedBy($user)->withProperties(['bin_engine_name' => $request->bin_engine_name, 'bin_engine_code' => $request->bin_engine_code, 'bin_engine_status' => $request->bin_engine_status])->log($this->addSuccess);
					//END ACTIVITY LOG
				}
			}
			//END CHECKING bincode.com if bin code is exist then get data
		}

		return Response::json(
			[
				'response' => 'success',
				'message' => 'Bin Number ' . $bin_engine->bin_number . ' was added',
				'bin_number' => $bin_engine->bin_number
			]
		);
	}
	/**
 	 * End Store new bin engine data.
 	 */

 /**
	 * Delete bin engine data.
	 * Table: bin_engines.
	 * Input: id
	 */
	public function destroyBin(Request $request)
	{
		$user = Auth::guard('admin')->user();

		$bin_engine = BinEngine::find($request->id);
		$bin_engine->delete();

		//ACTIVITY LOG
		activity($this->eventUpdate)->causedBy($user)->withProperties(['bin_number' => $request->bin_number])->log($this->destroySuccess);
		//END ACTIVITY LOG

		return Response::json(
			[
				'response' => 'success',
				'message' => 'Bin Number ' . $bin_engine->bin_number . ' was deleted',
				'bin_number' => $bin_engine->bin_number
			]
		);
	}
	/**
 	 * End Delete bin engine data.
 	 */

}
