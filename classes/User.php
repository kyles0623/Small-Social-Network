<?php


defined('ACCESS') or die('You do not have permission to be here');
class User
{
	private $db;
	private $username;
	private $password;
	private $first_name;
	private $last_name;



	public function __construct()
	{
		$this->db = DBConnector::getInstance();

	}
	public function setUser($username,$password)
	{

		$data = $this->db->getSingleRow('Select * FROM users where username = "'.sanitizeString($username).'" AND password = "'.md5(sanitizeString($password)).'"');

		if(!empty($data))
		{
			$this->username = $data['username'];
			$this->first_name = $data['first_name'];
			$this->last_name = $data['last_name'];
			$this->password = $data['password'];
		}
		else
			return false;


	}
	public function add($data)
	{
		$query = "INSERT into users SET username = '".$data['username']."', 
		password = '".md5($data['password'])."',
		first_name='".sanitizeString($data['firstname'])."',
		last_name='".sanitizeString($data['lastname'])."' ";

		$return = $this->db->query($query);

		return $return;
	}


	public function getData($fields=array("*"))
	{
		if(!is_array($fields))
		{
			$fields = array($fields);
		}
		$data = $this->db->getSingleRow('Select '.implode(',',$fields).' FROM users where username = "'.$this->username.'" AND password = "'.$this->password.'"');


		return $data;
	}
	public function authenticate($username,$password)
	{
		$data = $this->db->getSingleRow('Select * FROM users where username = "'.$username.'" AND password = "'.$password.'"');

		if(empty($data))
			return false;

		return true;
	}

	public function isUnique($username)
	{
		$data = $this->db->getSingleRow('Select * FROM users where username = "'.$username.'"');

		if(empty($data))
			return true;

		return false;
	}



}