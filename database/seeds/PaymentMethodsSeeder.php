<?php

use Illuminate\Database\Seeder;

use App\PaymentMethods;

class PaymentMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            ['payment_type' => 'visa', 'payment_method' => 'credit_card', 'status' => true, 'logo' => 'images/visa.png'],
            ['payment_type' => 'mastercard', 'payment_method' => 'credit_card', 'status' => true, 'logo' => 'images/mastercard.png'],
        ];

        foreach($methods as $m)
        {
            $result = PaymentMethods::where('payment_method', '=', $m['payment_method'])
                ->where('payment_type', '=', $m['payment_type'])
                ->first();

            if(!$result)
            {
                PaymentMethods::create($m);
            }
        }
    }
}
