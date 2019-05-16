<?php

use Illuminate\Database\Seeder;

use App\PaymentMethodRoutes;
use App\PaymentMethods;

class PaymentMethodRoutesSeederWallet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = PaymentMethods::where('payment_method', 'wallet')
        							->where('status', true)
        							->get();

        if($methods)
        {
	        foreach($methods as $method)
          {
              $result = PaymentMethodRoutes::where('payment_method_id', $method->id)
                  ->where('route', 'ccavenue')
                  ->first();

              if(!$result)
              {
    	        	PaymentMethodRoutes::insert([
    	        		['payment_method_id' => $method->id, 'route' => 'ccavenue', 'status' => true]
    	        	]);
              }
	        }
	   	}
    }
}
