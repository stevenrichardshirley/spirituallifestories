<?php
class db {
    private $db_user;
	private $db_pass;	
	private $db_name;	
	private $db_server;
	private $link;
	private $result_id;
	
	public function __construct($user='', $pass='', $name='', $server='localhost') {
		$config= new DATABASE_CONFIG();

	    if( IS_DEV == true ) {
    		$this->db_user = $config->test['login'];
    		$this->db_pass = $config->test['password'];
    		$this->db_name = $config->test['database'];
    		$this->db_server = $config->test['host'];
	    }
	    
	    if( IS_DEV == false ) {
    		$this->db_user = $config->master['login'];
    		$this->db_pass = $config->master['password'];
    		$this->db_name = $config->master['database'];
    		$this->db_server = $config->master['host'];
	    }
	
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

   function fetchrow($info) 
   {
       return mysql_fetch_row($info);
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