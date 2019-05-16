<?php

use Illuminate\Database\Seeder;

use App\PaymentMethods;
use App\Libraries\Banks;

class NetBankingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data = [];

      foreach(Banks::$banks as $bank)
      {
          $exist = PaymentMethods::where('payment_type', $bank)->where('payment_method', 'net_banking')->count();

          if(!$exist)
          {
          	PaymentMethods::create([
          		'payment_type' => $bank,
          		'payment_method' => 'net_banking',
              'logo' => '',
          		'status' => true
          	]);
          }
      }
    }
}
