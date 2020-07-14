<?php
$user = isset( $_SESSION['myusername'] );
$page_name = pathinfo($_SERVER['REQUEST_URI']);
$page_name = $page_name['filename'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Asbury Seedbed // Summit Content Management</title>

<link href="adminstyles.css" rel="stylesheet" type="text/css" />
<link href="css/demo_table.css" rel="stylesheet" type="text/css" />
<link href="css/ui-custom-theme/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/tags.css" />

<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.rowReordering.js"></script>

<script type="text/javascript" src="js/tags.min.js"></script>



<script>
	function deleteAlert(name,id) {
		var conBox = confirm("Are you sure you want to delete: " + name);
		if(conBox) {
	        location.href="<?=$_SERVER['PHP_SELF'];?>?id=" + id + "&cmd=delete&name=" + name;
		} else {
			return;
		}
	}
	
	$(function(){
		$('#date').datetimepicker({
			ampm: true
		});
	});
	
	$(document).ready(
		function() {
		    $('#data_sort').dataTable( {
				"sPaginationType": "full_numbers",
				"iDisplayLength": 50
			});
			
			/*
$('#data_sort').dataTable()
			.rowReordering({ 
			  sURL:"UpdateRowOrder.php",
			  fnAlert: function(message) { 
			                  alert(message);
			           }
			});
*/
			
	});
</script>


<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="ckfinder/ckfinder.js"></script>

</head>
<body>

<div id="fullWide">

	<div id="nav_column">
		<h1><a href="home.php">Summit Dashboard</a></h1>
		<ul>
			<li><a href="home.php" <?php if ($page_name == 'home') { echo 'class="selected"'; } ?>>Dashboard</a></li>
	    	<li><a href="articles.php" <?php if ($page_name == 'articles'|| $page_name == 'article_manage') { echo 'class="selected"'; } ?>>Articles</a></li>
	    	<li><a href="article_categories.php" <?php if ($page_name == 'article_categories' || $page_name == 'article_category_manage') { echo 'class="selected"'; } ?>>Article Categories</a></li>
	    	<li><a href="authors.php" <?php if ($page_name == 'authors' || $page_name == 'author_manage') { echo 'class="selected"'; } ?>>Authors</a></li>
	    	<li><a href="products.php" <?php if ($page_name == 'products' || $page_name == 'product_manage') { echo 'class="selected"'; } ?>>Store Products</a></li>
	    	<li><a href="briefing.php" <?php if ($page_name == 'briefing' || $page_name == 'briefing_manage') { echo 'class="selected"'; } ?>>Weekly Briefing</a></li>
<!-- 	    	<li><a href="banners.php" <?php if ($page_name == 'banners' || $page_name == 'banner_manage') { echo 'class="selected"'; } ?>>Home Banners</a></li> -->
	    	<li><a href="webpages.php" <?php if ($page_name == 'webpages' || $page_name == 'webpage_manage') { echo 'class="selected"'; } ?>>Web Page Content</a></li>
		</ul>
	</div>

<div id="content_column_fluid">
	    
	<div id="account_bar">
	
		<div id="site_title">
			<h2>Asbury Seedbed</h2><a href="<?php echo utils::url('home'); ?>" target="_blank">View Live Site</a>
		</div>

		<div id="account_info">
			<?php if ( $user ) { ?>Welcome back! <a href="index.php?logout=true"><strong>Logout [x]</strong></a><?php } ?>
		</div>
	</div>
	
	<div id="outside_margin">
	
	