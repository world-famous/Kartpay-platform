<?php

use Illuminate\Database\Seeder;

use App\PaymentMethods;
use App\Libraries\Wallets;

class PaymentMethodsSeederWallet extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = [];

        foreach(Wallets::$wallets as $wallet)
        {
            $result = PaymentMethods::where('payment_method', 'wallet')
                ->where('payment_type', $wallet)
                ->first();

            if(!$result)
            {
            	PaymentMethods::create([
            		'payment_type' => $wallet,
            		'payment_method' => 'wallet',
                    'logo' => ('images/wallets/' . ($wallet == 'Jana Cash' ? 'jana' : strtolower(str_replace(" ", "", $wallet))) . '_logo.png'),
            		'status' => true
            	]);
            }
        }
    }
}
