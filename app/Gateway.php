<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $fillable = [
                              'gateway_name'
                          ];

    /*
  	 * Get Name Attribute
  	 *
  	*/
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    /*
  	 * END Get Name Attribute
  	 *
  	*/

    /*
  	 * Get Gateway Types Class that related to Gateway
  	 *
  	*/
    public function gatewayTypes()
    {
        return $this->hasMany('App\GatewayType');
    }
    /*
  	 * END Get Gateway Types Class that related to Gateway
  	 *
  	*/
}
