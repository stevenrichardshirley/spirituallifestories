<?php

require_once 'includes/db_conn.php';
require_once 'includes/functions.php';
$dbc = new db();


// Define $myusername and $mypassword

$cleanpassword = mysql_real_escape_string($_POST['mypassword']);

$myusername = mysql_real_escape_string($_POST['myusername']); 
$mypassword = sha1($cleanpassword);

$query = "SELECT * FROM admin_users WHERE user = '{$myusername}' and password = '{$mypassword}' LIMIT 1";
$result = mysql_query($query);

// Mysql_num_row is counting table row
$count = mysql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row

if (mysql_fetch_row($result)) {
  /* access granted */
	session_register("myusername");
	session_register("mypassword"); 
	header("location: ./home.php");
}
else {

	header("Location: ./index.php?msg=Invalid%20Login");

}

?>
