<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Couriers extends Model
{
    protected $fillable = [
                          	'sr_no',
                          	'courier_name',
                          	'api_code'
                          ];
}
