<?php

use Illuminate\Database\Seeder;

use App\PaymentMethods;

class PaymentMethodsSeederDebitCard extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $debits = [
            ['payment_type' => 'visa', 'payment_method' => 'debit_card', 'status' => true, 'logo' => 'images/visa.png'],
            ['payment_type' => 'mastercard', 'payment_method' => 'debit_card', 'status' => true, 'logo' => 'images/mastercard.png']
        ];

        foreach($debits as $db)
        {
            $exist = PaymentMethods::where('payment_type', $db['payment_type'])
                ->where('payment_method', $db['payment_method'])
                ->first();

            if(!$exist)
            {
                PaymentMethods::create($db);
            }
        }
    }
}
