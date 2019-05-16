<?php

include_once('../vendor/autoload.php');

$environment = (new josegonzalez\Dotenv\Loader('../.env'))->parse()->toArray();

echo "cron job executed at " . time() . " please wait.. \n";

if(isset($environment['APP_URL'])){

	$url = $environment['APP_URL'] . '/restApi/backup/create';
	echo file_get_contents($url);	
}else{
	echo "Application URL Does not exist.";
}
