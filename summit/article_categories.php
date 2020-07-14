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
		$query= "DELETE FROM categories WHERE id= {$_GET['id']}";
	    $result= mysql_query($query);
	    $messagepass =  "Article category deleted! Hope you meant to do that, because it is gone.";
	}
	
	
	include('includes/header.php'); 
	  
?>

	<div id="title_bar">
		<h3>Article Categories</h3>
		<div class="add_button_holder"><a href="article_category_manage.php">Add New Article Category &raquo;</a></div>	
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
	            <th>Name</th>
	            <th width="2">&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php
	        $query = "SELECT * FROM categories";
			$result = mysql_query($query) or die ("Query failed");
			//let's get the number of rows in our result so we can use it in a for loop
			$numofrows = mysql_num_rows($result);
			
			for($i = 0; $i < $numofrows; $i++) {
			    $row = mysql_fetch_array($result); //get a row from our result set
			    echo "<td><a href=\"article_category_manage.php?id={$row['id']}\"><img src=\"images/edit.png\" alt=\"Edit\" /></a></td>";
			    echo "<td><a href=\"article_category_manage.php?id={$row['id']}\">{$row['name']}</a></td>";
			    echo "<td><a href=\"javascript: deleteAlert('{$row['name']}','{$row['id']}')\"><img src=\"images/delete.png\" /></a></td>";
		    	echo "</tr>";
			}
			//now let's close the table and be done with it
			?>
			</tbody>
		</table>
	</div>
	</div> <!-- content_holder -->
	
<?php include('includes/footer.php'); ?>
		
