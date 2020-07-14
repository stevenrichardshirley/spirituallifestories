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
		$query= "DELETE FROM photos WHERE id= {$_GET['id']}";
	    $result= mysql_query($query);
	    $messagepass =  "Photo deleted! Hope you meant to do that, because it is gone.";
	}
	
	include('includes/header.php'); 
	  
?>

	<div id="title_bar">
		<h3>Photos</h3>
		<div class="add_button_holder"><a href="photo_manage.php">Add New Photo &raquo;</a></div>	
	</div>
	<div id="content_holder">
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	} elseif (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	?>
	<div class="innerpad">
	    
	    <table width="100%" border="0" cellspacing="0" id="data_sort">
	    	<thead>
	    	<tr>
	        	<th width="2">&nbsp;</th>
	        	<th>Featured</th>
	        	<th>Title</th>
	        	<th>Location</th>
	        	<th>Description</th>
	        	<th width="2">&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php
	        $query = "SELECT * FROM photos";
			$result = mysql_query($query) or die ("Query failed");
			$numofrows = mysql_num_rows($result);
			
			for($i = 0; $i < $numofrows; $i++) {
			    $row = mysql_fetch_array($result);
			    echo "<tr>";
			    echo "<td><a href=\"photo_manage.php?id={$row['id']}\"><img src=\"images/edit.png\" alt=\"Edit\" /></a></td>";
			    if ( $row['featured'] == '1' ) {
			    	echo "<td><img src=\"images/checked.png\" /></td>";
			    } else {
			    	echo "<td>&nbsp;</td>";
			    }
			    echo "<td><a href=\"photo_manage.php?id={$row['id']}\">{$row['title']}</a></td>";
			    $location = admin::location($row['location_id']);
			    echo "<td>" .$location[0]->title. "</td>";
			    echo "<td>" .utils::limit_words($row['description'], 20). "</td>";
			    echo "<td><a href=\"javascript: deleteAlert('" .$row['title']. "','" .$row['id']. "');\"><img src=\"images/delete.png\" alt=\"Delete\"></a></td>";
		    	echo "</tr>";
			}
			?>
			</tbody>
		</table>
	</div>
	</div> <!-- content_holder -->
	
<?php include('includes/footer.php'); ?>
		
