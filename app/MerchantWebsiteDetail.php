<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantWebsiteDetail extends Model
{
    protected $fillable = [
                              'merchant_id',
                              'domain_name',
                          ];
}
