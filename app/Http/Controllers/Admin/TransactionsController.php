<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Response;
use Auth;

class TransactionsController extends Controller
{

	/**
     * Log Event
     *
     * @var string
     */
	protected $eventUpdate = 'Merchant Transaction';

	/**
     * Log Description
     *
     * @var string
     */
	protected $updateSuccess = 'Merchant Transaction updated successfuly';

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
    public function index(Request $request)
    {
	    $searchColumns = [
		    'kartpay_id' => 'Kartpay ID',
		    'created_at' => 'Date',
		    'merchant_order_id' => 'Order ID',
		    'merchant_order_amount'=> 'Order Amount',
		    'status' => 'status'
	    ];

    	$this->validate($request, [
    		'size' => 'integer|min:1|max:150',
		    'column' => 'in:'.implode(',', array_keys($searchColumns))
	    ]);

    	$pageSize = $request->get('size', 10);
    	$currentColumn = $request->get('column');
    	$searchString = $request->get('search', '');


	    $query = Transaction::query();
	    if ( $currentColumn && trim( $searchString ) ) {
		    if ( $currentColumn == 'created_at' ) {
		    	$this->validate($request, ['search' => 'date']);
			    $start = Carbon::createFromTimestamp( strtotime( $searchString ) );
			    $end   = $start->copy()
			                   ->addDay();
			    $query->whereBetween( $currentColumn, [ $start, $end ] );
		    }else{
		    	$query->where($currentColumn, 'like', '%' . $searchString . '%');
		    }
	    }

        $query = $query->with('access_code');

	    $transactions = $query->orderBy('created_at', 'desc')
		    ->paginate($pageSize);
	    $currentColumn = $currentColumn?:'kartpay_id';

        return view('panel.backend.pages.transactions.index',
	        compact('transactions','searchColumns', 'searchString', 'currentColumn','pageSize'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $kartpay_id
     * @return \Illuminate\Http\Response
     */
    public function show($kartpay_id)
    {
        $transaction = Transaction::find($kartpay_id);
        return view('panel.backend.pages.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $kartpay_id
     * @return \Illuminate\Http\Response
     */
    public function edit($kartpay_id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $kartpay_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kartpay_id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $kartpay_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kartpay_id)
    {
        //
    }

		public function search(Request $request)
    {
        $transactions = Transaction::where($request->field, 'like', '%' . $request->search . '%')->get();

				$newTransaction = array();
				$x = 1;
				foreach($transactions as $transaction)
				{
					$objTransaction= array();
					$objTransaction[] = $x;
					$objTransaction[] = $transaction->kartpay_id;
					$objTransaction[] = $transaction->created_at->format("d/M/Y H:i");
					$objTransaction[] = $transaction->merchant_order_id;
					$objTransaction[] = $transaction->merchant_order_amount;
					$objTransaction[] = $transaction->status;

					$viewMore = '<a href="' . route("admins.transactions.show", $transaction->kartpay_id) . '" class="btn btn-link text-muted">View More</a>';
					$objTransaction[] = $viewMore;

					$newTransaction[] = $objTransaction;
					$x++;
				}

				return $newTransaction;
    }

		/**
     * Set Status
     *
     * @param  int $kartpay_id, string $status
     * @return \Illuminate\Http\Response
     */
    public function setStatus(Request $request)
    {
			$user = Auth::guard('admin')->user();
			$transaction = transaction::where('kartpay_id', $request->kartpay_id)->first();

			//ACTIVITY LOG
			activity($this->eventUpdate)->causedBy($user)->withProperties([
																		'attributes' =>
																		[
																			'kartpay_id' => $request->kartpay_id,
																			'status' => $request->status,
																			'status_message' => $request->status_message,
																		],
																		'old' =>
																		[
																			'kartpay_id' => $transaction->kartpay_id,
																			'status' => $transaction->status,
																			'status_message' => $transaction->status_message,
																		],
																	])->log($this->updateSuccess);
			//END ACTIVITY LOG

			$transaction->status = $request->status;
			$transaction->status_message = 'Set to Success by Admin';
			$transaction->save();

			return Response::json([
									'response' => 'Success',
									'kartpay_id' => $transaction->kartpay_id,
									'status' => $transaction->status,
								  ]);
    }
		/**
     * END Set Status
     *
     * @param  int $kartpay_id, string $status
     * @return \Illuminate\Http\Response
     */
}
