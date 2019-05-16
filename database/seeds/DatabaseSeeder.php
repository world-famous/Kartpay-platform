<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    		$this->call(FirstAdminData::class);
    		$this->call(CreditCardInitSeederToGateway::class);
        $this->call(CouriersSeeder::class);
        $this->call(PaymentMethodsSeeder::class);
        $this->call(PaymentMethodRoutesSeeder::class);
        $this->call(PaymentMethodsSeederDebitCard::class);
        $this->call(PaymentMethodRoutesSeederDebitCard::class);
        $this->call(NetBankingSeeder::class);
        $this->call(NetBankingRoutesSeeder::class);
        $this->call(PaymentMethodsSeederWallet::class);
        $this->call(PaymentMethodRoutesSeederWallet::class);
        //$this->call(UpdateMerchantIds::class);
    }
}
