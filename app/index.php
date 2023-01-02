<?php

use Src\Modules\Cache\CacheFactory;

require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/config/config.php';
require __DIR__ . '/src/config/lang.php';


ini_set('display_errors', DISPLAY_DEBUG);
header("Content-Type:application/json");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");

define( "DEFAULT_LANGUAGE" , $lang[$config['lang']] ); //set default language


require __DIR__ . '/src/routers/api.php';