<?php
ini_set('display_errors',1); 
 error_reporting(E_ALL);

define('ACCESS',1);
define('DS',DIRECTORY_SEPARATOR);
require_once('init/init.php');


$DBConnector = DBConnector::getInstance();

$user = new User();

$session = Session::getInstance();

require_once('init/auth.php');

if(!AUTH)
{

	$page = 'login';

	if(!in_array(Get('function'),$allowed_functions))
		$function = 'login';
	else
		$function = Get('function');
}
else
{
	$page = Get('page')!=''?Get('page'):'home';
	$function = Get('function')!=''?Get('function'):'index';
}

$page = ucwords($page);
$PageClass = new $page($page,$function);
$PageClass->$function();

