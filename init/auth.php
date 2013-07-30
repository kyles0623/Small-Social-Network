<?php

$authorized = false;
$fields = $session->getFields(array('username','password'));

if(isset($fields['username']) && isset($fields['password']))
{
	$authorized = $user->authenticate($fields['username'],$fields['password']);
}

define('AUTH',$authorized);


