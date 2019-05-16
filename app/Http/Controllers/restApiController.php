<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\restApiForm;
use Validator;

class restApiController extends Controller
{
    //
    public function restApiPostForm(Request $request)
    {
    	# code..
    		$transationId = 6345363;
    		$Merchant_Id_lenght = 6;
    		$access_key_lenght = 10;
	      $kartpay_id_length = 6;
    		$Merchant_Id = 'KP'.rand(pow(10, $Merchant_Id_lenght-1), pow(10, $Merchant_Id_lenght)-1);

    		$access_key  =  rand(pow(10, $access_key_lenght-1), pow(10, $access_key_lenght)-1).rand(pow(10, $access_key_lenght-1), pow(10, $access_key_lenght)-1);
	      $kartpay_id = rand(pow(10, $kartpay_id_length-1), pow(10, $kartpay_id_length)-1);;

        $sha512 = hash('sha512', $Merchant_Id.$access_key);

    		$v = Validator::make($request->all() , [
					'currency'=> 'required|alpha|max:3',
					'merchant_order_amount' => 'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
					'Customer_email' => 'required|email',
					'merchant_order_id' => 'required|max:20',
					'customer_phone' => 'required|regex:/(91)[0-9]{10}/'
		     ]);

    		if($v->fails())
    		{
    			return redirect()->route('failUrl')->with(['errors' => $v->errors(),] );
    		}
    		else
    		{

    			return redirect()->route('paymentGateway')
    			->with([	'status' => 'success' ,
    						'amount' => $request->merchant_order_amount ,
    						'transationId' => $transationId,

    				]);

    			echo "<h1>Valid Inputs</h1>.<br>";
    			echo "Merchant_Id: ". $Merchant_Id . "<br>";
    			echo "access_key: ". $access_key . "<br>";
    			echo "kartpay_id: ". $kartpay_id. "<br>";
    			echo "sha512: ". $sha512. "<br>";
    		}
    }


    public function paymentGateway()
    {
    	# code...
    	return view('restApi/paymentGateway');

    }

    public function failUrl()
    {
    	return view('restApi/fail-url-page');
    }

  	public function testEncryption(Request $request)
  	{
  		return hash('sha512', $request->merchant_id . $request->access_key . $request->merchant_order_id . $request->merchant_order_amount . $request->currency . $request->customer_email . $request->customer_phone);
  	}

  	public function testEncryptionOrderStatus(Request $request)
  	{
  		return hash('sha512', $request->merchant_id . $request->access_key . $request->merchant_order_id);
  	}

  	public function testPaymentIframe(Request $request)
  	{
  		return view('restApi/testPaymentIframe');
  	}
}
