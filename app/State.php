<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
                              'state_name'
                          ];

    /*
  	 * Get City Class that related to State
  	 *
  	*/
  	public function cities()
    {
        return $this->hasMany('App\City');
    }
    /*
  	 * END Get City Class that related to State
  	 *
  	*/

    /*
  	 * Get Country Class that related to State
  	 *
  	*/
  	public function country()
    {
        return $this->belongsTo('App\Country');
    }
    /*
  	 * END Get Country Class that related to State
  	 *
  	*/
}
