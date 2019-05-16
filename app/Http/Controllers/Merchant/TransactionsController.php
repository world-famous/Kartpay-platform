<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Merchant;
use App\AccessKey;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchant = auth('merchant')->user();

        /* $transactions = Transaction::leftJoin('access_keys', 'transactions.merchant_id', '=', 'access_keys.merchant_id')
									->where('access_keys.user_id', '=', $merchant->id)
									//->where('access_keys.type', '=', 'live')
									->select(
												'transactions.kartpay_id',
												'transactions.merchant_id',
												'transactions.access_key',
												'transactions.order_id',
												'transactions.currency',
												'transactions.order_amount',
												'transactions.customer_email',
												'transactions.customer_phone',
												'transactions.success_url',
												'transactions.failed_url',
												'transactions.encryption',
												'transactions.paid_at',
												'transactions.status',
												'access_keys.type',
                        'transactions.created_at',
												'access_keys.user_id'
											)
									->get(); */

        $transactions = Transaction::where('merchant_id', $merchant->id)->get();

        foreach($transactions as $transaction)
        {
            $decryptTransactionAccessKey = $transaction->access_key;
            $accessKeys = AccessKey::where('merchant_id', $merchant->id)->get();
            foreach($accessKeys as $accessKey)
            {
              $decryptAccessKey = $accessKey->access_key;
              if($decryptTransactionAccessKey == $decryptAccessKey)
              {
                $transaction->type = $accessKey->type;
              }
            }
        }

        return view('merchant.backend.pages.transactions.index', compact('transactions'));
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
      return view('merchant.backend.pages.transactions.show', compact('transaction'));
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
			$objTransaction[] = $transaction->order_id;
			$objTransaction[] = $transaction->order_amount;
			$objTransaction[] = $transaction->status;

			$viewMore = '<a href="' . route("admins.transactions.show", $transaction->kartpay_id) . '" class="btn btn-link text-muted">View More</a>';
			$objTransaction[] = $viewMore;

			$newTransaction[] = $objTransaction;
			$x++;
		}

		return $newTransaction;
    }
}
