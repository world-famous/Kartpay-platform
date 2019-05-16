<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    protected $fillable = [
                          	'payment_type',
                          	'payment_methods',
                          	'status',
                            'logo'
                          ];

    /**
    * @var $payment_type (array)
    */
    public static $payment_type = [
                                  	'visa' => 'Visa',
                                  	'mastercard' => 'Master Card'
                                  ];

    /**
    * @var $payment_method (array)
    */
    public static $payment_method = [
                                    	'credit_card' => 'Credit Card',
                                    	'debit_card' => 'Debit Card',
                                    	'net_banking' => 'Net Banking',
                                      'wallet' => 'Wallet'
                                    ];

    /**
    * @var $status (boolean)
    */
    public static $status = [
                            	'0' => 'Inactive',
                            	'1' => 'Active'
                            ];


    public static function activeMethods($method)
    {
        return self::where('status', true)->where('payment_method', $method)->get()->toArray();
    }
}
