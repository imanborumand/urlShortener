<?php

use Src\Modules\Cache\CacheFactory;

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/config/config.php';
require __DIR__ . '/src/config/lang.php';


ini_set('display_errors', DISPLAY_DEBUG);


if (isset($_SERVER['HTTP_ORIGIN'])) {
	header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
	header('Access-Control-Allow-Credentials: true');
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	}
	
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	}
	exit(0);
}



define( "DEFAULT_LANGUAGE" , $lang[$config['lang']] ); //set default language

//print_r(json_decode(file_get_contents('php://input'), true));exit;
require __DIR__ . '/src/routers/api.php';