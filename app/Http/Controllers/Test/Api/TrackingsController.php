<?php

namespace App\Http\Controllers\Test\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use Illuminate\Routing\Controller as BaseController;

use App\AftershipCouriers;
use App\Couriers;
use App\AccessKey;

use App\Libraries\Aftership;

class TrackingsController extends BaseController
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

    public function track(Request $request){

        $errors = [];

        $v = Validator::make($request->all(), [
            'merchant_id'           => 'required',
            'access_key'            => 'required',
            'courier_code'              => 'required',
            'tracking_number'     => 'required'
        ], [
            'merchant_id.required' => 'Merchant ID is required',
            'access_key.required' => 'Access Key is required',
            'courier_code.required' => 'Courier Code is required',
            'tracking_number.required' => 'Tracking Number is required'
        ]);

         if ($v->fails()) {

            $errors = $v->errors()->getMessages();
            return response()->json([
                'errors' => $errors
            ]);

        }

        $access_key = AccessKey::where('merchant_id', $request->merchant_id)
            ->where('type', 'test')->get()
            ->filter(function ($data) use ($request) {
                return $data->access_key == $request->access_key;
            })
            ->first();

        if (!$access_key) {
            return response()->json([
                'errors' => [
                    '401' => 'Unauthorized',
                ],
            ], 401);
        }

        $courier = Couriers::where('api_code', $request->courier_code)->first();

        if(!$courier){
            $errors[] = "Courier code is invalid.";
            return response()->json([
                'errors' => $errors
            ], 422);
        }

        //task #215 - disable sending details to aftership server
        $track_request_id = "92965205280821e0b1cfc8";

        return response()->json([
            'Track_Request_ID' => $track_request_id,
            'Courier_code' => $request->courier_code,
            'Tracking_Number' => $request->tracking_number,
            'Status' => 'Accepted'
        ]);

    }
}
