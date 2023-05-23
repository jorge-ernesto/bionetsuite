<?php

ini_set('display_errors', 0);

define('DS', "/");
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('APP_PATH', ROOT . 'application' . DS);

require_once APP_PATH . 'Config.php';
require_once APP_PATH . 'Autoload.php';

try{
	session_start();
    Bootstrap::run(new Request);
}
catch(Exception $e){
    echo $e->getMessage();
}