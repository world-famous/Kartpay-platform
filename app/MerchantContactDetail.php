<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantContactDetail extends Model
{
    protected $fillable = [
                              'merchant_id',
                              'owner_name',
                              'owner_email',
                              'owner_mobile_number',
                              'owner_address',
                              'owner_state',
                      	      'owner_city',
                              'owner_country',
                              'owner_pin_code',
                          ];
}
