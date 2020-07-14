<? 
require_once('../app/bootstrap.php'); 
session_start();
if(!isset( $_SESSION['myusername'] )){
	header("location: ./index.php");
} 
?>

<?php
	require_once 'includes/db_conn.php';
	require_once 'includes/functions.php';
	$dbc = new db();
?>
    
<?php include('includes/header.php'); ?>

	<div id="title_bar">
		<h3>Choose Section to Manage</h3>
	</div>
	<div id="content_holder">
		<div class="innerpad">

		    <div class="admin_section">
		    	<a href="articles.php"><img src="images/adminpic-articles.jpg" alt="Locations" /><br />Manage Articles</a>
		    </div>  
		    
		    <div class="admin_section">
		    	<a href="article_categories.php"><img src="images/adminpic-article_categories.jpg" alt="Services" /><br />Manage Article Categories</a>
		    </div>
		    
			<div class="admin_section">
		    	<a href="authors.php"><img src="images/adminpic-authors.jpg" alt="Authors" /><br />Manage Authors</a>
		    </div>  
		    
		    <div class="admin_section">
		    	<a href="products.php"><img src="images/adminpic-products.jpg" alt="Products" /><br />Manage Products</a>
		    </div>
		    
		    <div class="admin_section">
		    	<a href="briefing.php"><img src="images/adminpic-briefing.jpg" alt="Briefing" /><br />Manage Briefing</a>
		    </div>  
		    
		    <!--
<div class="admin_section">
		    	<a href="banners.php"><img src="images/adminpic-banners.jpg" alt="Banners" /><br />Manage Home Banners</a>
		    </div>
-->
		    
		    <div class="admin_section">
		    	<a href="webpages.php"><img src="images/adminpic-webpages.jpg" alt="Webpages" /><br />Manage Webpage Content</a>
		    </div>  
		    	    
	    </div> <!-- innerpad -->    
   	</div> <!-- content_holder -->
	    	    	
<?php include('includes/footer.php'); ?>