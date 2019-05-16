<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
	protected $table = 'citys';

  protected $fillable = [
											      'city_name'
											  ];

	/*
	 * Get State Class that related to City
	 *
	*/
	public function state()
  {
      return $this->belongsTo('App\State');
  }
	/*
	 * END Get State Class that related to City
	 *
	*/
}
