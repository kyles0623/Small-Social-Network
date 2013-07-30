<?php

class Home extends PageClass
{
	public function __construct($page,$function)
	{
		 parent::__construct($page,$function);
	}

	public function index()
	{


		//required on all
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}

}