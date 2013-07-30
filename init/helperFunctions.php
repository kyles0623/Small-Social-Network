<?php
defined('ACCESS') or die('You do not have permission to be here');

function debug($msg)
{
	echo "<pre>";
	print_r($msg);
}

function Get($name)
{
	if(isset($_GET[$name]))
		return $_GET[$name];

	return '';
}
