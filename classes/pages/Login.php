<?php



class Login extends PageClass
{
	public function __construct($page,$function)
	{
		 parent::__construct($page,$function);
	}
	public function login()
	{

		if(isset($_POST['username']) && isset($_POST['password']))
		{

			$user = new User();

			if($user->authenticate($_POST['username'],md5($_POST['password'])))
			{
				$user->setUser($_POST['username'],$_POST['password']);
				$session = Session::getInstance();
				$session->add($user->getData());
				header('Location: index.php?page=home');
			}
		}

		
		$this->template = 'blank';
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}

	public function register()
	{
		if(isset($_POST['firstname']))
		{
			$data = $_POST;
			$user = new User();

			$errors = array();
			//validation
			if($data['username'] == '')
				$errors[] = 'A username is required';
			else if(!$user->isUnique($data['username']))
			{
				$errors[] = 'Your username must be unique';
			}
			if($data['firstname'] == '' || $data['lastname'] == '')
				$errors[] = 'A first and last name are required';
			if($data['password'] == '')
				$errors[] = 'A password is required';
			
			if(empty($errors))
			{
				$user->add($data);
				$this->login();
				exit;
			}
		}
		$this->template = 'blank';
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");	
	}

	public function ajaxCheckUserName()
	{
		if(isset($_POST['username']))
		{
			$user = new User();

			if($user->isUnique($_POST['username']))
				echo 'unique';
			else
				echo 'failed';

		}
		else
			echo 'failed';
		exit;
	}


	public function logout()
	{
		$session = Session::getInstance();
		$session->destroy();
		header('Location: index.php');
	}

}