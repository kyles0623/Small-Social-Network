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
	private $mail;
	public function __construct()
	{
		$this->db = DBConnector::getInstance();
		$this->mail = new Mail();
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
			$this->about = $data['about'];
			$this->id = $data['id'];
			return true;
		}
		else
			return false;


	}
	public function setFriendUser($id)
	{

		$data = $this->db->getSingleRow("Select * FROM users where id = $id ");

		if(!empty($data))
		{
			
			$this->username = $data['username'];
			$this->password = $data['password'];
			$this->first_name = $data['first_name'];
			$this->last_name = $data['last_name'];
			$this->email = $data['email'];
			$this->phone = $data['phone'];
			$this->created = $data['created'];
			$this->about = $data['about'];
			$this->id = $data['id'];
			return true;
		}
		else
			return false;


	}
	public function isFriendsWith($id)
	{
		$query = "SELECT count(*) as count FROM users_friends where  user_id = $this->id AND friend_id = $id";

		$result = $this->db->getSingleRow($query);
		return $result['count'];

	}
	public function getFriendsData()
	{
		
				$query = "Select 
							users.*,
							images.image_path as image_path 
							FROM users 
							left join images
							on images.user_id = users.id and images.isDefault = 1
							where users.id in (SELECT friend_id from users_friends where user_id=$this->id and approved = 1)";
				
				$data = $this->db->getAssoc($query);

				return $data;

	}
	public function getFriendData($friend_id)
	{
		$data = $this->db->getSingleRow("Select * FROM users where id in (SELECT user_id from users_friends where user_id=$this->id and friend_id=$friend_id  and approved = 1)");


		return empty($data)?false:$data;
		
	}
	private function getUserData($id)
	{
		$data = $this->db->getSingleRow("Select * FROM users where id = $id");

		return empty($data)?false:$data;
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
	public function sendRequest($id)
	{
		
		$query = "INSERT into users_friends (user_id,friend_id) 
					VALUES ($id,$this->id),($this->id,$id)";
		
		$return = $this->db->query($query);
		$errors = mysql_error();
		if(!empty($errors))
		{
			return $errors;
		}
		else
		{
			$this->mail->sendMessage(MESSAGE_FRIEND_REQUEST_TYPE, $id,$this);
		}

		return '';


	}
	public function acceptRequest($id)
	{
		
		$query = "UPDATE users_friends SET approved = 1 WHERE (user_id = $id AND friend_id = $this->id) OR (user_id = $this->id AND friend_id = $id) ";
		
		$return = $this->db->query($query);
		$errors = mysql_error();
		if(!empty($errors))
		{
			return $errors;
		}
		

		return '';


	}
	public function sendMessage($to_user,$data = array())
	{

		$type = MESSAGE_DEFAULT_TYPE;
		$from_user = $this->id;
		$users = $this->getFriendsIDs();
		if(!is_array($to_user))
		{
			if(in_array($to_user,$users))
				$this->mail->sendMessage($type,$to_user,$this,$data);
		}
		else
		{
			
			foreach($to_user as $user_id)
			{
				if(in_array($user_id,$users))
					$this->mail->sendMessage($type,$user_id,$this,$data);
			}
		}
	}
	public function getMessages()
	{
		$messages =  $this->mail->getMessages($this->id);
		foreach($messages as &$message)
		{
			$id = $message['from_id'];
			$message['friend'] = $this->getUserData($id);
		}

		return $messages;
	}
	public function getMessage($id)
	{
		$query = "SELECT * FROM messages where user_id = $this->id && id = $id";



		$message = $this->db->getSingleRow($query);

		$id = $message['from_id'];
		$message['friend'] = $this->getUserData($id);


		return $message;
	}
	public function getMessageCountNew()
	{
		return $this->mail->getMessageCount($this->id,0);
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
		if(empty($fields))
			return false;
		$this->setUser($fields['username'],$fields['password']);
		return $this;

	}
	public function searchAllUsers($query)
	{
		$terms = explode(" ",sanitizeString($query));
		$ignoreTerms = array("'id'","'created'","'password'");
		$dbName = $this->db->getDBName();
		$fieldsQuery = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$dbName."' AND `TABLE_NAME`='users' 
							AND `COLUMN_NAME` NOT IN (".implode(",",$ignoreTerms).") ";
		$fields = $this->db->getArray($fieldsQuery);


		$likeQuery = "";
		$count = array();


		foreach($terms as $term)
		{
			$likeQuery .= "(";
			foreach($fields as $field)
			{
				$likeQuery .= $field[0]	." LIKE '%".$term."%' OR ";
			}
			$likeQuery = substr($likeQuery,0,-3);
			$likeQuery .= ") AND ";
		}
		$likeQuery = substr($likeQuery,0,-4);
		
		$query = "SELECT 
					users.id,
					first_name,
					last_name,
					username,
					created, 
					images.image_path, 
			       	users_friends.approved  as isFriend 
					FROM users 
					left join images 
						on images.user_id = users.id 
					left join users_friends 
						on users_friends.user_id = $this->id 
						and users_friends.friend_id = users.id
					WHERE users.id != $this->id AND ".$likeQuery;
		
		return $this->db->getAssoc($query);
	}
	public function updateUser($info)
	{
		$dataQuery = "";
		foreach($info as $key => $value)
		{
			$dataQuery .= $key."='".sanitizeString($value)."'";
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
		$query = 'Select '.implode(',',$fields).' FROM users where id='.$this->id;
		
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
	public function markMessageAsRead($id)
	{
		$query = "UPDATE messages set isRead = 1 WHERE id = $id";

		return $this->db->query($query);

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
		$query = "SELECT Count(id) from users_friends where user_id = $this->id and approved = 1";
		
		$res = $this->db->getResult($query);

		return $res[0];
	}
	public function getMutualFriends($id)
	{

		$user = new User();
		$user->setFriendUser($id);

		$usersFriends = $user->getFriendsIDs();

		$myFriends = $this->getFriendsIDs();

		$mutualFriendsIDs = array_intersect($usersFriends,$myFriends);
		if(empty($mutualFriendsIDs))
			return array();
		return $this->getUsersByIDS($mutualFriendsIDs);

	}
	private function getUsersByIDS($ids)
	{
		$idsQuery = implode(",",$ids);

		$query = "SELECT * FROM users where id in ($idsQuery) ";
		
		return $this->db->getAssoc($query);
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

		$query = "SELECT friend_id FROM users_friends where approved = 1 AND user_id = $this->id";
		
		$result = $this->db->getArrayofSingleValues($query);
		return $result;
	}
	public function getFriendsWithStatuses()
	{
		$friendsIDS = $this->getFriendsIDs();
		$friendsIDSString = implode(",",$friendsIDS);
		$friendString = "(";
		$friendString .= $friendsIDSString;
		$friendString .= ")";
		
		$query = "SELECT statuses . * , user . *
			FROM statuses 
			left join users user 
			on user.id = statuses.user_id 
			where statuses.user_id in ".$friendString." 
			order by statuses.created desc LIMIT 10";
		
		$results = $this->db->getAssoc($query);
		return $results;

	}
	public function addStatus($status)
	{
		$status = sanitizeString($status);

		$query = "INSERT INTO statuses SET text='$status', user_id=$this->id";

		return $this->db->query($query);
	}

	public function deleteStatus($statusID)
	{
		$statusID = sanitizeString($statusID);

		$query = "DELETE FROM statuses where id=$statusID AND user_id = $this->id";

		return $this->db->query($query);
	}


}