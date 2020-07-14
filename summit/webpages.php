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

	if( isset($_GET['id']) && isset($_GET['cmd']) ) {
		$query= "DELETE FROM webpages WHERE id= {$_GET['id']}";
	    $result= mysql_query($query);
	    $messagepass =  "Webpage deleted! Hope you meant to do that, because it is gone.";
	}
		
	include('includes/header.php'); 
 
?>

	<div id="title_bar">
		<h3>Web Page Content</h3>
		<div class="add_button_holder"><a href="webpage_manage.php">Add New Page</a></div>	
	</div>
	<div id="content_holder">
	<div class="innerpad">
	    <p>This is the page management page. Here you manage the individual of the site that live under each main section. Look for the title of the page you want to manage on the front end of the site and click the name below to manage. You can also use the search tool on this page to immediately filter the results to more quickly find your page.</p>
	    
	    <table width="100%" border="0" cellspacing="0" id="data_sort">
	    	<thead>
	    	<tr>
	        	<th width="2">&nbsp;</th>
	        	<th>Name</th>
	        	<th>Slug</th>
	        	<th>Page Title</th>
	        	<th width="2">&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php
	        $query = "SELECT * FROM webpages ORDER BY name";
			$result = mysql_query($query) or die ("Query failed");
			//let's get the number of rows in our result so we can use it in a for loop
			$numofrows = mysql_num_rows($result);
			
			for($i = 0; $i < $numofrows; $i++) {
			    $row = mysql_fetch_array($result);
				echo "<tr>";
			    echo "<td><a href=\"webpage_manage.php?id={$row['id']}\"><img src=\"images/edit.png\" alt=\"Edit\" /></a></td>";
			    echo "<td><a href=\"webpage_manage.php?id={$row['id']}\">{$row['name']}</a></td>";
			    echo "<td>{$row['slug']}</td>";
			    echo "<td>{$row['page_title']}</td>";
			    echo "<td><a href=\"javascript: deleteAlert(" .$row['name']. "," .$row['id']. ");\"><img src=\"images/delete.png\" alt=\"Delete\"></a></td>";
		    	echo "</tr>";
			}
			?>
			</tbody>
		</table>
	</div>
	</div> <!-- content_holder -->
	
<?php include('includes/footer.php'); ?>
		
