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
		$query= "DELETE FROM eye_candy WHERE id= {$_GET['id']}";
	    $result= mysql_query($query);
	    $messagepass =  "Eye Candy deleted! Hope you meant to do that, because it is gone.";
	}
	
	include('includes/header.php'); 
	  
?>

	<div id="title_bar">
		<h3>Tools of the Trade</h3>
		<div class="add_button_holder"><a href="tool_of_trade_manage.php">Add New Tool of the Trade &raquo;</a></div>	
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
	        	<th></th>
	        	<th>Featured</th>
	        	<th>Title</th>
	        	<th>Slug</th>
	        	<th>Description</th>
	            <th>&nbsp;</th>
	        </tr>
	        </thead>
	        <tbody>
	        <?php
	        $query = "SELECT * FROM tools_of_the_trade";
			$result = mysql_query($query) or die ("Query failed");
			$numofrows = mysql_num_rows($result);
			
			for($i = 0; $i < $numofrows; $i++) {
			    $row = mysql_fetch_array($result);
			    echo "<tr>";
			    echo "<td><a href=\"tool_of_trade_manage.php?id={$row['id']}\"><img src=\"images/edit.png\" alt=\"Edit\" /></a></td>";
			    if ( $row['featured'] == '1' ) {
			    	echo "<td><img src=\"images/checked.png\" /></td>";
			    } else {
			    	echo "<td>&nbsp;</td>";
			    }
			    echo "<td><a href=\"tool_of_trade_manage.php?id={$row['id']}\">{$row['title']}</a></td>";
			    echo "<td>{$row['slug']}</td>";
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
		
