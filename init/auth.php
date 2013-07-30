<?php

$authorized = false;
$fields = $session->getFields(array('username','password'));

if(isset($fields['username']) && isset($fields['password']))
{

	$authorized = $user->authenticate($fields['username'],$fields['password']);

	if($authorized)
	{
		$user->setUser($fields['username'],$fields['password']);
	}
}



$allowed_functions = array('register','login','ajaxCheckUserName');

define('AUTH',$authorized);


