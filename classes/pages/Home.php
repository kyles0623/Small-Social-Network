<?php

class Home extends PageClass
{
	private $user;
	public function __construct($page,$function)
	{
		$temp = new User();
		$this->user = $temp->getLoggedInUser();
		 parent::__construct($page,$function);
	}

	public function index()
	{
		$user = new User();
		$user = $user->getLoggedInUser();



		if(isset($_POST['addStatus']))
		{
			$user->addStatus($_POST['status']);
		}

		$defaultImage = $user->getDefaultImage();
		$friendCount = $user->getFriendCount();
		$statuses = $user->getStatuses();
		$friendsStatuses = $user->getFriendsStatuses();
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

		require_once(BASE_PATH.'templates'.DS.$this->template.".php");
	}


	private function uploadImage($name,$inputName,$default = false)
	{
				if($default)
					$name.='.default';


				
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
}