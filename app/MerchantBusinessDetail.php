<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantBusinessDetail extends Model
{
    protected $fillable = [
                              'merchant_id',
                              'business_legal_name',
                              'business_trading_name',
                              'business_registered_address',
                              'business_state',
                              'business_city',
                      	      'business_country',
                              'business_pin_code',
                          ];
}
