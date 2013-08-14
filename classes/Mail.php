<?php

defined('ACCESS') or die('You do not have permission to be here');

class Mail
{
	private $db;

	public function __construct()
	{
		$this->db = DBConnector::getInstance();
	}

	public function getMessages($user_id,$details = array())
	{
		


		$query = "SELECT * FROM messages where  user_id = $user_id";
		if(isset($details['id'] ))
			$query .= " AND id = ".$details['id']." ";
		else
		{
			$query .= isset($details['from_id'])?" AND from_id = ".$details['from_id']:"";
			$query .= isset($details['message_type_id'])?" AND message_type_id = ".$details['message_type_id']:"";
			$query .= isset($details['read'])?" AND isRead = ".$details['read']:"";

			if(isset($details['between']))
			{
				if(is_array($details['between']))
				{
					$query .= " AND date(created) between date(".$details['between'][0].") and date(".$details['between'][1].") ";
				}
			}
		}
		
		$query .= " order by created desc";
		$messages = $this->db->getAssoc($query);

		return $messages;
	}
	public function getMessageCount($user_id,$read=null)
	{
		$query = "SELECT count(id) as count from messages where user_id = $user_id ";
		if(isset($read))
			$query .= " AND isRead = ".$read;

		$result =  $this->db->getSingleRow($query);
		
		return $result['count'];

	}
	public function sendMessage($type,$to_id,$from_user,$data = array())
	{
		
		if($type == MESSAGE_FRIEND_REQUEST_TYPE)
		{
			$query = "SELECT * FROM mail_templates WHERE message_type = ".$type;
			$mail_template = $this->db->getSingleRow($query);
			if($mail_template == null)
			{
				die('There was an error in the application. If this continues pleas contact your System administrator.');
			}

			$file_contents = file_get_contents(MAIL_TEMPLATE_PATH.$mail_template['template_name']);

			

			$file_contents = str_replace("{IMG_SRC}", $from_user->getDefaultImage(), $file_contents);
			$file_contents = str_replace("{NAME}", $from_user->first_name." ".$from_user->last_name, $file_contents);
			$file_contents = str_replace("{ID}", $from_user->id, $file_contents);
			$message = addslashes($file_contents);
			$query = "INSERT INTO messages SET user_id = $to_id, from_id = $from_user->id,title = \" ".$mail_template['name']."\" ,message=\"$message\",message_type_id = $type";
			$result = $this->db->query($query);
			
			return $result;
		}
		else if ($type == MESSAGE_DEFAULT_TYPE)
		{
			
			$query = "SELECT * FROM mail_templates WHERE message_type = ".$type;
			$mail_template = $this->db->getSingleRow($query);
			if($mail_template == null)
			{
				die('There was an error in the application. If this continues pleas contact your System administrator.');
			}

			$file_contents = file_get_contents(MAIL_TEMPLATE_PATH.$mail_template['template_name']);

			$file_contents = str_replace("{BODY}", $data['body'],$file_contents);
			$message = addslashes($file_contents);
			$query = "INSERT INTO messages SET user_id = $to_id, from_id = $from_user->id,title = \" ".$data['title']."\" ,message=\"$message\",message_type_id = $type";
			$result = $this->db->query($query);
			
			return $result;

		}
	}



	
}
