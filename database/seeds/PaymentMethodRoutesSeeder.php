<?php

use Illuminate\Database\Seeder;

use App\PaymentMethodRoutes;
use App\PaymentMethods;

class PaymentMethodRoutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $method = PaymentMethods::where('payment_method', 'credit_card')
        							->where('status', true)
        							->first();
        if($method)
        {
            $result = PaymentMethodRoutes::where('payment_method_id', '=', $method->id)
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
