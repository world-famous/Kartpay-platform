<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\PaymentMethods;

class PaymentMethodsController extends Controller
{
    /**
      * Load List of Payment Method Data
      *
      */
    public function list($payment_method="")
    {
      if(!isset(PaymentMethods::$payment_method[$payment_method]))
      {
          abort(404);
      }

    	$data['list'] = PaymentMethods::where('payment_method', $payment_method)->get();
    	$data['payment_method'] = $payment_method;

    	return view('panel.backend.pages.payment_methods.list', $data);
    }
    /**
      * END Load List of Payment Method Data
      *
      */

    /**
      * Set Status Payment Method
      *
      */
    public function setStatus($id, $status)
    {
    	$success = false;

    	$pm = PaymentMethods::find($id);
    	$pm->status = $status;

    	$success = $pm->save();

    	return response()->json([
    		'success' => $success
    	]);
    }
    /**
      * END Set Status Payment Method
      *
      */
}
