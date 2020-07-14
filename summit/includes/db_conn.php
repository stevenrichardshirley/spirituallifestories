<?php
class db {
    private $db_user;
	private $db_pass;	
	private $db_name;	
	private $db_server;
	private $link;
	private $result_id;
	
	public function __construct($user='root', $pass='root', $name='seedbed', $server='localhost') {
	
		$this->db_user = $user;
		$this->db_pass = $pass;
		$this->db_name = $name;
		$this->db_server = $server;
	
		$this->connect();
	
	}
	
	function connect()
	{
		$this->link = @mysql_connect($this->db_server,$this->db_user,$this->db_pass) or die("can't connect to database");
		@mysql_select_db($this->db_name,$this->link) or die("can't select db (".$this->db_name.")");		
	}	
	
	function disconnect()
	{
		@mysql_close($this->link);
	}

   function getArray($info)
   {
   	   return mysql_fetch_array(mysql_query($info));
   }

   function query($info) 
   {
       return mysql_query($info);
   }

   function fetch($info) 
   {
       return mysql_fetch_array($info);
   }

   function num($info) 
   {
       return mysql_num_rows($info);
   }
   
   function lastinsert() 
   {
       return mysql_insert_id();
   }
   
   function affected()
   {
 	 return mysql_affected_rows();
   }

}
?>