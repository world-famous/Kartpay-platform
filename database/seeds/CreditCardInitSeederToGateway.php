<?php

use Illuminate\Database\Seeder;
use App\Gateway;
use App\GatewayType;

class CreditCardInitSeederToGateway extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Gateway::count() == 0)
    		{
    			$gateway = new Gateway();
    			$gateway->gateway_name = 'Credit Card';
    			$gateway->save();

    			if(GatewayType::count() == 0)
    			{
    				$gatewayType = new GatewayType();
    				$gatewayType->gateway_type_name = 'Visa';
    				$gatewayType->is_enable = '1';
    				$gatewayType->route = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiatePayloadTransaction';
    				$gatewayType->gateway_id = $gateway->id;
    				$gatewayType->save();

    				$gatewayType = new GatewayType();
    				$gatewayType->gateway_type_name = 'Mastercard';
    				$gatewayType->is_enable = '1';
    				$gatewayType->route = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiatePayloadTransaction';
    				$gatewayType->gateway_id = $gateway->id;
    				$gatewayType->save();
    			}
    		}
    }
}
