<?php

namespace App\Http\Controllers\Merchant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\AftershipCouriers;
use App\Libraries\Aftership;

class CouriersController extends Controller
{
	/**
	* Sync data from shipment tracker API
	*
	* @return $success (boolean)
	*/
    public function sync(){
    	$aftership = new Aftership();
    	$couriers = $aftership->couriers();

    	$insert = [];

    	if(!$couriers['data']['couriers']){
    		return false;
    	}
    	
    	foreach($couriers['data']['couriers'] as $c){
    		$details = $c;
    		
    		foreach($c as $key => $x){
    			if(is_array($x)){
    				$details[$key] = json_encode($x);
    			}
    		}

    		$insert[] = $details;
    	}

    	$success = AftershipCouriers::insert($insert);
         
    	return compact('success');
    }
}
