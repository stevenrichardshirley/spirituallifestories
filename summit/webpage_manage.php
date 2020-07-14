<? 
session_start();
if ($_GET['logout'] == 'true' ) {
	session_destroy();
} else {
	if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
	}
}

	require_once('../app/bootstrap.php');
	require_once 'includes/db_conn.php';
	require_once 'includes/functions.php';
	$dbc = new db();
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM webpages WHERE id=" . $current;
		$result = mysql_query($query);
		$webpage = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
	
		
	
		$timestamp = date("Y-m-d, g:i:s");
		$parent_id = mysql_clean($_POST['parent_id']);
		$name = mysql_clean($_POST['name']);
		$slug = slug($name);
		$page_title = mysql_clean($_POST['page_title']);
		$seo_description = mysql_clean($_POST['seo_description']);
		$content = mysql_clean($_POST['content']);
		
		if (isset($_GET['id'])) {
		    $id = mysql_clean($_GET['id']);
		
		    $query = "UPDATE webpages SET
		    		parent_id = '{$parent_id}',
		    		name = '{$name}',
		    		page_title = '{$page_title}',
		    		seo_description = '{$seo_description}',
		    		content = '{$content}',
		    		timestamp = '{$timestamp}'
		    	WHERE id = {$id}";

		    
		} else {
		
			$exist = admin::exist($slug);
			if ( $exist == "true" ) {
				$messagefail = "There is already a page with this name. Please choose a new name for this page.";
			} else {
		
		    $query = "INSERT INTO webpages (
		    	parent_id, name, slug, page_title, seo_description, content, timestamp
		    	) VALUES (
		    	'{$parent_id}', '{$name}', '{$slug}', '{$page_title}', '{$seo_description}', '{$content}', '{$timestamp}'
		    	)";
		    }
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
		    // success
		    if (isset($_GET['id'])) {
		    	$messagepass .= "The web page has been updated!";
		    } else {
		    	header('Location:webpages.php');
		    }
		} else {
		    // failed
		    $messagefail = "There has been an error creating the web page!!";
		    $messagefail .= "<br />". mysql_error();
		}
		
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
	$current = $_GET['id'];
	$query = "SELECT * FROM webpages WHERE id=" . $current;
	$result = mysql_query($query);
	$webpage = mysql_fetch_array($result);
	
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3><?php echo $webpage['name']; ?></h3>
		<?php } else { ?>
			<h3>Add Webpage</h3>
		<?php } ?>
	
		<div class="add_button_holder"><a href="webpages.php"> &laquo; Back to Webpages</a></div>
	</div>
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	} elseif (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	?>
	<div id="content_holder">
	<div id="inner_left_column">

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$_GET['id']}"; }?>" method="post" enctype="multipart/form-data" >
	
	
		<div class="form_row">
			<label for="name">Page Name</label>
			<input type="text" name="name" id="name" value="<?php if (isset($_GET['id'])) { echo $webpage['name']; } else { echo $_POST['name']; }; ?>" class="field100" tabindex="1" />
		</div>
		
		<div class="form_row">
			Link to this page: <strong>/<?php echo $webpage['slug']; ?></strong> &nbsp;|&nbsp; <a href="/<?php echo $webpage['slug']; ?>" target="_blank">View Page</a>
		</div>
		
		<div class="form_row">
			<label for="page_title">Page Title <small>( This will show as the title on the page. )</small></label>
			<input type="text" name="page_title" id="page_title" value="<?php if (isset($_GET['id'])) { echo $webpage['page_title']; } else { echo $_POST['page_title']; }; ?>" class="field100" tabindex="2" />
		</div>
		
		<div class="form_row">
			Content<br />
			<textarea name="content" id="content" tabindex="4"><?php if (isset($_GET['id'])) { echo $webpage['content']; } else { echo $_POST['content']; }; ?></textarea>
			<script>
				CKEDITOR.replace( 'content',
   				 {
   				     toolbar : 'Base'
   				 });
			</script>
		</div>
		
		<div class="form_row">
			<input type="submit" name="submit" value="Update Web Page" class="form_button" />
		</div>
	
	</div>
	<div id="inner_right_column">
		<div class="form_row">
			SEO Description <small>( This is the description that will be hidden in the site and read by search engines )</small><br />
			<textarea name="seo_description" id="seo_description" tabindex="3" rows="2"><?php if (isset($_GET['id'])) { echo $webpage['seo_description']; } else { echo $_POST['seo_description']; }; ?></textarea>
		</div>
	</div>
	</form>
	
	</div>
	    		
<?php include('includes/footer.php'); ?>