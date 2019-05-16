<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'countrys';

  protected $fillable = [
											      'country_name'
											  ];

	/*
	 * Get State Class that related to Country
	 *
	*/
	public function states()
  {
      return $this->hasMany('App\State');
  }
	/*
	 * END Get State Class that related to City
	 *
	*/

	/*
	 * Get City Class that related to Country through State Class
	 *
	*/
	public function cities()
  {
      return $this->hasManyThrough('App\City', 'App\State');
  }
	/*
	 * END Get City Class that related to Country through State Class
	 *
	*/
}
