<?php
//Required at end of each view function
//require_once(BASE_PATH.'templates'.DS.$this->template.".php");


defined('ACCESS') or die('You do not have permission to be here');

class Friend extends PageClass
{
	
	public function __construct($page,$function)
	{
		
		 parent::__construct($page,$function);
	
	}
	public function viewAll()
	{
		$friends = $this->user->getFriendsData();

		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}

	public function search()
	{

		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}
	public function profile()
	{
		$mutualFriends = array();
		$user_id = Get('id');
		$friend = new User();

		$isFriend = $this->user->isFriendsWith($user_id);
		
		if($isFriend){
			$friend->setFriendUser($user_id);
			$mutualFriends = $friend->getMutualFriends($this->user->id);
			$statuses = $friend->getStatuses();
			$friendCount = $friend->getFriendCount();
			$defaultImage = $friend->getDefaultImage();
		}

		$defaultImage;
		$errors = array();
		if($friend == false)
			$errors[] = "You are not friends with this person. Go Away!";

		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}
	public function ajaxSendRequest()
	{
		$id = sanitizeString($_POST['id']);
		
		if($id == $this->user->id)
		{
			echo 'You can\'t add yourself as a friend';
			die();
		}
		$request = $this->user->sendRequest($id);
		if(empty($request))
			echo 'sent';
		else
			echo 'failed';
	}
	public function ajaxAcceptRequest()
	{
		$id = sanitizeString($_POST['id']);
		
		if($id == $this->user->id)
		{
			echo 'You can\'t add yourself as a friend';
			die();
		}

		$request = $this->user->acceptRequest($id);
		if(empty($request))
			echo 'accepted';
		else
			echo 'failed';
	}
	public function ajaxSearch()
	{
		$searchTerm = sanitizeString($_POST['searchTerm']);
		$friends = $this->user->searchAllUsers($searchTerm);
		if(empty($friends))
			echo 'empty';
		else
			echo json_encode($friends);
	}

}