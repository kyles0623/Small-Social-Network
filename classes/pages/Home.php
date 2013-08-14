<?php
defined('ACCESS') or die('You do not have permission to be here');

class Home extends PageClass
{
	
	public function __construct($page,$function)
	{
		
		 parent::__construct($page,$function);
	}

	public function index()
	{
		



		if(isset($_POST['addStatus']))
		{
			$this->user->addStatus($_POST['status']);
		}

		$defaultImage = $this->user->getDefaultImage();
		$friendCount = $this->user->getFriendCount();
		$statuses = $this->user->getStatuses();
		if($friendCount > 0)
			$friendsStatuses = $this->user->getFriendsWithStatuses();
		//required on all
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}

	public function profile()
	{
		$user = new User();
		$user = $user->getLoggedInUser();
		$userUpdated = false;

		if(isset($_POST) && !empty($_POST))
		{
			$user->updateUser($_POST);
			if(isset($_FILES) && !empty($_FILES['image']['name']) )
			{

				$this->uploadImage($this->user->username,'image',true);
			}
			$userUpdated = true;
					
		}
		
		
		$defaultImage = $user->getDefaultImage();
		$noDefault = false;
		if(basename($defaultImage) == "noavatar.png")
			$noDefault = true;
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}

	public function deleteStatus()
	{
		$id = Get('status');
		$this->user->deleteStatus($id);
		header('Location: index.php');
	}
	private function uploadImage($name,$inputName,$default = false)
	{
		


		$date = date('Y-m-d:H.i.s');
		$name = $name."-".$date;
		$saveto = "/home/kshambli/public_html/MP3/images/".$name;
	    

	    $typeok = TRUE;
     	switch($_FILES['image']['type'])
	    {
	        case "image/gif":   
	        $type = 'gif';
	      	break;
	        case "image/jpeg":  // Both regular and progressive jpegs
	        case "image/pjpeg": 
	        $type = 'jpeg';
	        break;
	        case "image/png":   
	        $type = 'png';
	       	break;
	        default:            $typeok = FALSE;  break;
	    }
	    $saveto = $saveto.".".$type;
	    move_uploaded_file($_FILES[$inputName]['tmp_name'], $saveto);

	    switch($_FILES['image']['type'])
	    {
	        case "image/gif":   
	        
	        $src = imagecreatefromgif($saveto); break;
	        case "image/jpeg":  // Both regular and progressive jpegs
	        case "image/pjpeg": 
	        $src = imagecreatefromjpeg($saveto);  break;
	        case "image/png":   
	        
	        $src = imagecreatefrompng($saveto); break;
	        default:            $typeok = FALSE;  break;
	    }

	    $aveto = $saveto.$type;
	    


	    if ($typeok)
	    {
	        
	        list($w, $h) = getimagesize($saveto);

	        $max = 400;
	        $tw  = $w;
	        $th  = $h;
	        
	        if ($w > $h && $max < $w)
	        {
	            $th = $max / $w * $h;
	            $tw = $max;
	        }
	        elseif ($h > $w && $max < $h)
	        {
	            $tw = $max / $h * $w;
	            $th = $max;
	        }
	        elseif ($max < $w)
	        {
	            $tw = $th = $max;
	        }
	        
	        $tmp = imagecreatetruecolor($tw, $th);
	        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
	        imageconvolution($tmp, array(array(-1, -1, -1),
	            array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
	        imagejpeg($tmp, $saveto);
	        imagedestroy($tmp);
	        imagedestroy($src);


	        if(file_exists($saveto))
	        {
	        	$this->user->InsertImage($name.".".$type,$this->user->id,true);
	        }
	    }
	}
	public function checkMessages()
	{

		$messages = $this->user->getMessages();
		if(empty($messages))
			$messages = array();
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}
	public function viewMessage()
	{
		$message_id = Get('id');
		$errors = array();
		if($message_id == "")
			$errors[] = "There was an error processing your request";

		$message = $this->user->getMessage($message_id);

		if(empty($message))
			$errors[] = "You donot have access to view this message";

		if(empty($errors))
		{
			$this->user->markMessageAsRead($message_id);
		}

		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}
	public function NewMessage()
	{
		$title = '';
		$body = '';
		$to_users = array();
		$messageSent = false;
		if(isset($_POST) && !empty($_POST))
		{
			$title = sanitizeString($_POST['title']);
			$body = sanitizeString($_POST['body']);
			$to_users = isset($_POST['to_users'])?$_POST['to_users']:array();
			$errors = array();
			if(empty($_POST['body']))
				$errors[] = "A body is required";
			if(empty($_POST['title']))
				$errors[] = "A body is required";
			if(empty($_POST['to_users']))
			{
				$errors[] = "You have to send this to someone!";
			}

			if(empty($errors))
			{
				//Send message
				$data = array('body'=>$body,'title'=>$title);
				$this->user->sendMessage($to_users,$data);
				$messageSent = true;
			}

			
		}
		$friends = $this->user->getFriendsData();
		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}
}