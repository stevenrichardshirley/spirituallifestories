<?
require_once('../app/bootstrap.php'); 
session_start();
if ($_GET['logout'] == 'true' ) {
	session_destroy();
} else {
	if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
	}
}

?>
<?php
	require_once 'includes/db_conn.php';
	require_once 'includes/functions.php';
	$dbc = new db();
	
	if (isset($_GET['id']))
	{
		$current = $_GET['id'];
	}
	
	include('includes/header.php'); 
 
	if(!empty($messagepass)) 
	{
	  	echo "<div class=\"messagepass\">{$messagepass}</div>";
	  } elseif (!empty($messagefail)) {
	  	echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	  
	if(!empty($errors)) 
	{
	  	echo "<div class=\"messagewarning\">Please review the following fields: <br /> ";
	  		foreach($errors as $error) {
	  		echo "<strong>{$error}</strong> | ";
	  	}
	  	echo "</div>";
	}
?>

	<div class="add_button_holder"><a href="user_manage.php">[+] Add User</a></div>


	    <h2>Users</h2><br />
	    <table width="100%" border="0" cellspacing="0" cellpadding="10" id="data_sort">
    	<thead>
    	<tr style="background: #DFDCD7; color: #545454;" align="left">
        	<th>Username</th>
        	<th>Email</th>
        	<th>Church Name</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $query = "SELECT user_id, username, email, church_name, state_id FROM users ORDER BY date";
		$result = mysql_query($query) or die ("Query failed");
		//let's get the number of rows in our result so we can use it in a for loop
		$numofrows = mysql_num_rows($result);
		
		for($i = 0; $i < $numofrows; $i++) {
		    $row = mysql_fetch_array($result); //get a row from our result set
		    if($i % 2) { //this means if there is a remainder
		        echo "<tr bgcolor=\"#f1efee\">";
		    } else { //if there isn't a remainder we will do the else
		        echo "<tr bgcolor=\"#fff\">";
		    }
		    echo "<td><a href=\"user_manage.php?id={$row['user_id']}\">{$row['username']}</a></td>";
		    echo "<td>" .$row['email']. "</td>";
		    echo "<td>" .$row['church_name']. "</td>";
		    echo "<td><a href=\"user_manage.php?id={$row['user_id']}\">Edit</a></td>";
	    	echo "</tr>";
		}
		//now let's close the table and be done with it
		?>
		</tbody>
	</table>
	
<?php include('includes/footer.php'); ?>
		
