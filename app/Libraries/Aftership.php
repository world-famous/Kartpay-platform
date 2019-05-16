<?php

namespace App\Libraries;

class Aftership{

	private $key = "";

	public function __construct()
	{
		$this->key = config('aftership.live_key');
	}

	/**
	* Couriers EndPoint
	*
	* @return $response (json)
	*/
	public function couriers()
	{
		$couriers = new \AfterShip\Couriers($this->key);
		$response = $couriers->get();

		return $response;
	}

	/**
	* Get Tracking
	*
	* @param $slug (string)
	* @param $tracking_number (string)
	*/
	public function trackings($slug = "", $tracking_number = "")
	{
		$response = false;

		try
		{
			$trackings = new \AfterShip\Trackings($this->key);
			$response = $trackings->get($slug, $tracking_number);
			return $response;
		}
		catch(\AfterShip\Exception\AftershipException $e)
		{
			$errors[] = $e->getMessage();
			return $errors;
		}
		return $response;
	}
}
