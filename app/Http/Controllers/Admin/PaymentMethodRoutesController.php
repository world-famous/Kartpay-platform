<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\PaymentMethodRoutes;
use App\PaymentMethods;

use Illuminate\Support\Facades\Input;

class PaymentMethodRoutesController extends Controller
{
    /**
      * Load Payment Method Route Page
      *
      */
    public function method($id)
    {
    	$data['payment_method'] = PaymentMethods::find($id);
    	$data['routes'] = PaymentMethodRoutes::where('payment_method_id', $id)->get();

    	return view('panel.backend.pages.payment_method_routes.method', $data);
    }
    /**
  		* END Load Payment Method Route Page
  		*
  		*/

    /**
      * Update Status Payment Method Route Page
      *
      */
    public function updateStatus($id)
    {
    	$input = Input::all();
    	$routes = PaymentMethodRoutes::where('payment_method_id', $id)->get();

    	foreach($routes as $route)
      {
    		$row = PaymentMethodRoutes::find($route['id']);
    		$row->status = isset($input['routes'][$route['id']]) ? 1 : 0;
    		$row->save();
    	}
    	return redirect('payment_method_routes/method/' . $id)->with('success', 'Successfully Updated Status.');
    }
    /**
      * END Update Status Payment Method Route Page
      *
      */
}
