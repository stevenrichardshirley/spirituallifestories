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
		$query= "DELETE FROM products WHERE id= {$_GET['id']}";
	    $result= mysql_query($query);
	    $messagepass =  "Product deleted! Hope you meant to do that, because it is gone.";
	}
	
	
	include('includes/header.php'); 
	  
?>

<script type="text/javascript">
    $(function() {
        $("#sortable").sortable({
            placeholder: 'ui-state-highlight',
            stop: function(i) {
                placeholder: 'ui-state-highlight'
                $.ajax({
                    type: "GET",
                    url: "updatedb.php",
                    data: $("#sortable").sortable("serialize")});
            }
        });
        $("#sortable").disableSelection();
    });
</script>

	<div id="title_bar">
		<h3>Product Display Order</h3>
		<div class="add_button_holder"><a href="/cron/seedbed_store.php">Click Here to Run Product Import from Shopify &raquo;</a></div>	
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
	    	
		<p>Drag and drop the products in the order you would like them to display in the site. The order is automatically updated in the database and on the frontend of the site upon release. Do not drag these around unless you want to change the order on the front of the site.</p>

		<ul id="sortable">
        <?php
        $query = "SELECT * FROM products ORDER BY sorting_order ASC";
		$result = mysql_query($query) or die ("Query failed");
		
    	while($row = mysql_fetch_array($result)) {
    	$date = date('Y-m-d');
		?>
		<li id="item_<?php echo $row['id']; ?>" class="ui-state-default">
		<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?php echo $row['title']; ?>
		<div class="delete"><a href="javascript: deleteAlert('<?php echo $row['title']; ?>','<?php echo $row['id']; ?>');"><img src="images/delete.png" alt="Delete"></a></div>
		</li>
		<? } ?>
		</ul>
	</div>
	</div> <!-- content_holder -->
	
<?php include('includes/footer.php'); ?>
		
