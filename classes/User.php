<?php


defined('ACCESS') or die('You do not have permission to be here');
class User
{
	private $db;
	public $username;
	private $password;
	public $first_name;
	public $last_name;
	public $email;
	public $phone;
	public $created;
	public $id;
	public function __construct()
	{
		$this->db = DBConnector::getInstance();

	}
	public function setUser($username,$password)
	{

		$data = $this->db->getSingleRow('Select * FROM users where username = "'.sanitizeString($username).'" AND password = "'.$password.'"');

		if(!empty($data))
		{
			
			$this->username = $data['username'];
			$this->password = $data['password'];
			$this->first_name = $data['first_name'];
			$this->last_name = $data['last_name'];
			$this->email = $data['email'];
			$this->phone = $data['phone'];
			$this->created = $data['created'];
			$this->id = $data['id'];
			return true;
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
	public function getDefaultImage()
	{
		$query = "SELECT * FROM images where user_id = ".$this->id." AND isDefault = 1 order by id desc";
		
		$data  = $this->db->getSingleRow($query);

		if(empty($data) || $data == null )
		{
			return BASE_URL.'images'.DS.'noavatar.png';
		}
		else
			return BASE_URL.'images'.DS.$data['image_path'];
	}
	public function getLoggedInUser()
	{
		$session = Session::getInstance();
		$fields = $session->getFields(array('username','password'));
		$this->setUser($fields['username'],$fields['password']);
		return $this;

	}
	public function updateUser($info)
	{
		$dataQuery = "";
		foreach($info as $key => $value)
		{
			$dataQuery .= $key."='".$value."'";
			$dataQuery .= ",";
		}
		$dataQuery = substr($dataQuery,0,-1);
		$query = "UPDATE users SET ".$dataQuery." Where id = ".$this->id;
		return $this->db->query($query);
	}
	public function getData($fields=array("*"))
	{
		if(!is_array($fields))
		{
			$fields = array($fields);
		}
		$query = 'Select '.implode(',',$fields).' FROM users where username = "'.$this->username.'" AND password = "'.$this->password.'"';
		
		$data = $this->db->getSingleRow($query);


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

	public function InsertImage($path,$user_id,$default=false)
	{

		if($default == true)
			$default = 1;
		else
			$default = 0;
		if($default == true)
		{
			$query = "UPDATE images SET isDefault=0 where user_id=".$user_id." AND isDefault=1";
		}
		$query = "INSERT INTO images SET user_id=".$user_id.",image_path='".$path."',isDefault=".$default;

		$this->db->query($query);
	}

	public function getFriendCount()
	{
		$query = "SELECT Count(id) from users_friends where user_id = $this->id";
		
		$res = $this->db->getResult($query);

		return $res[0];
	}

	public function getStatuses()
	{
		$query = "SELECT * FROM statuses where user_id = $this->id order by created desc LIMIT 5";

		$results = $this->db->getAssoc($query);
		if(empty($results))
			$results = array();
		return $results;


	}
	public function getFriendsIDs()
	{
		$query = "SELECT friend_id FROM users_friends where user_id = $this->id";
		$result = $this->db->getAssoc($query);
		return $result;
	}
	public function getFriendsInformation()
	{

	}
	public function getFriendsStatuses()
	{
		$friendsIDS = $this->getFriendsIDs();

		$friendString = "(";
		$friendString .= implode(",",$friendsIDS);
		$friendString .= ")";

		$query = "SELECT * statuses left join users on users.id = statuses.user_id where user_id in ".$friendString." order by created desc LIMIT 10";
	}
	public function addStatus($status)
	{
		$status = sanitizeString($status);

		$query = "INSERT INTO statuses SET text='$status', user_id=$this->id";

		return $this->db->query($query);
	}


}