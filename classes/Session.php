<?php

defined('ACCESS') or die('You do not have permission to be here');
class Session
{
	private $session;

	private static $instance;

	private function __construct()
	{
		session_start();
		$this->session = $_SESSION;
	}

	public function add($data)
	{
		if($data == false)
			return false;
		foreach($data as $key=>$value)
		{
			$_SESSION[$key] = $value;
		}
		$this->updateSession();
		return true;
	}
	public function updateSession()
	{
		$this->session = $_SESSION;
	}

	public static function getInstance()
	{
		if(self::$instance == null)
			self::$instance = new Session();

		return self::$instance;
	}
	public function getSession()
	{
		return $this->session;
	}
	public function getFields($fields)
	{
		$temp = array();

		foreach($fields as $field)
		{

			if(isset($this->session[$field]))
				$temp[$field] = $this->session[$field]; 
			
			 
		}

		return $temp;
	}

	public function destroy()
	{
		session_destroy();
	}

}