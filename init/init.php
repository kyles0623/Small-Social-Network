<?php
defined('ACCESS') or die('You do not have permission to be here');


define('BASE_PATH','/home/kshambli/public_html/MP3/');
define('BASE_URL','http://lamp.cse.fau.edu/~kshambli/MP3/');

$includes = array(
	get_include_path(),
	BASE_PATH.'classes'
	);


ini_set('include_path',implode(PATH_SEPARATOR ,$includes));
include('helperFunctions.php');

function __autoload($classname) {
    $filename = BASE_PATH.'classes'.DS. $classname .".php";
    include_once($filename);
}