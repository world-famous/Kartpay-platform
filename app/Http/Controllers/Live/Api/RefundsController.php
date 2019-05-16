<?php

namespace App\Http\Controllers\Live\Api;

use App\AccessKey;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;
use Validator;

class RefundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $errors = [];

        $hash_data = hash('sha512', $request->merchant_id . $request->access_key . $request->kartpay_id . $request->refund_amount . $request->reason);

        $request['same_encryption'] = $hash_data;

        $v = Validator::make($request->all(), [
            'access_key'    => 'required',
            'merchant_id'   => 'required',
            'reason'        => 'required',
            'kartpay_id'    => 'required|exists:transactions,id,merchant_id,' . $request->merchant_id,
            'refund_amount' => 'required|numeric|min:0.1|regex:/^\d*(\.\d{1,2})?$/',
            'encryption'    => 'required|same:same_encryption',
        ], [
            'kartpay_id.exists'   => "Kartpay ID doesn't exists.",
            'encryption.same'     => 'Hash key is invalid',
            'refund_amount.regex' => 'Refund amount invalid format',
        ]);

        $access_key = AccessKey::where('merchant_id', $request->merchant_id)
            ->where('type', 'live')->get()
            ->filter(function ($data) use ($request) {
                return $data->access_key == $request->access_key;
            })->first();

        if (!$access_key) {
            return response()->json([
                'errors' => [
                    401 => 'Unauthorized',
                ],
            ], 401);
        }

        $transaction = Transaction::find($request->kartpay_id);

        if ($transaction && ($transaction->refundable_amount <= $request->refund_amount)) {

            $errors[] = [
                'refund_amount' => 'Refund amount must be lesser than refundable amount',
            ];
        }

        if ($v->fails() || count($errors)) {

            foreach ($v->errors()->toArray() as $k => $error) {

                $errors[] = [
                    'field'       => $k,
                    'description' => $error[0],
                ];
            }

            if (!$v->fails()) {
                $refund = $transaction->refunds()->create([
                    'refund_amount' => $request->refund_amount,
                    'reason'        => $request->reason,
                    'merchant_id'   => $request->merchant_id,
                    'access_key'    => $request->access_key,
                    'status'        => 'Failed',
                ]);

                return response()->json([
                    'request_id' => $refund->id,
                    'kartpay_id' => $refund->transaction_id,
                    'reason'     => $refund->reason,
                    'status'     => $refund->status,
                ], 422);
            }

            return response()->json([
                'errors' => [
                    'status_code' => 422,
                    'message'     => 'Missing/invalid parameters',
                    'parameters'  => $errors,
                ],
            ], 422);
        }

        $refund = $transaction->refunds()->create([
            'refund_amount' => $request->refund_amount,
            'reason'        => $request->reason,
            'merchant_id'   => $request->merchant_id,
            'access_key'    => $request->access_key,
            'status'        => 'Success',
        ]);

        return response()->json([
            'request_id' => $refund->id,
            'kartpay_id' => $refund->transaction_id,
            'reason'     => $refund->reason,
            'status'     => $refund->status,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
