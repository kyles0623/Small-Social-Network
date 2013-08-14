<?php
defined('ACCESS') or die('You do not have permission to be here');
//Singleton Database Class
class DBConnector
{
	//Current Connection
	private static $currConnection = null;
	
	private $dbhost  = 'localhost';    // Unlikely to require changing
	private $dbname  = '';       // Modify these...
	private $dbuser  = '';   // ...variables according
	private $dbpass  = '';   // ...to your installation
	

	private function __construct()
	{
		mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) or die(mysql_error());
		mysql_select_db($this->dbname) or die(mysql_error());

	}

	public function getDBName()
	{
		return $this->dbname;
	}

	public function __destruct()
	{
		mysql_close();
	}

	public static function getInstance()
	{
		if(static::$currConnection == null)
			static::$currConnection = new DBConnector();

		return static::$currConnection;
	}


	public function query($query)
	{
		$result = mysql_query($query) or die(mysql_error());
		return $result;
	}
	public function getResult($query)
	{
		$result = mysql_query($query) or die(mysql_error());

		return mysql_fetch_row($result);
	}

	public function getArray($query)
	{
		$result = mysql_query($query) or die(mysql_error());
		$tempArray = array();
		while($row = mysql_fetch_row($result) )
		{
			$tempArray[] = $row;
		}
		return $tempArray;

	}

	public function getAssoc($query)
	{
		$result = $this->query($query);

		$tempArray = array();
		while($row = mysql_fetch_assoc($result) )
		{
			$tempArray[] = $row;
		}

		return $tempArray;
	}
	public function getArrayofSingleValues($query)
	{
		$result = $this->query($query);

		$tempArray = array();
		$count = mysql_num_rows($result);

		for($i=0;$i<$count;$i++)
			$tempArray[] = mysql_result($result,$i);
		
		return $tempArray;
		
	}
	public function getSingleRow($query)
	{
		$data = $this->getAssoc($query);
		if(isset($data[0]) && is_array($data[0]))
			return $data[0];
		else
			return $data;
	}

}