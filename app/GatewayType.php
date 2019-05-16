<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class GatewayType extends Model
{
    protected $fillable = [
                              'gateway_type_name',
                              'is_enable',
                              'route',
                              'gateway_id'
                          ];

    /*
  	 * Get Gateway Class that related to Gateway Type
  	 *
  	*/
    public function gateway()
    {
        return $this->belongsTo('App\Gateway');
    }
    /*
  	 * END Get Gateway Class that related to Gateway Type
  	 *
  	*/
}
