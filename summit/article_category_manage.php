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
		$query = "SELECT * FROM categories WHERE id=" . $current;
		$result = mysql_query($query);
		$cat = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
	
		if ($_POST['active'] == 'yes') {
			$active = 1;
		} else {
			$active = 0;
		}
	
		$timestamp = date("Y-m-d, g:i:s");
		$name = mysql_clean($_POST['name']);
		$slug = slug($name);
		$description = mysql_clean($_POST['description']);
		
		if (isset($_GET['id'])) {
		    $id = mysql_clean($_GET['id']);
		
		    $query = "UPDATE categories SET
		    		active = '{$active}',
		    		name = '{$name}',
		    		slug = '{$slug}',
		    		description = '{$description}',
		    		timestamp = '{$timestamp}'
		    	WHERE id = {$id}";

		    
		} else {
		
		    $query = "INSERT INTO categories (
		    	active, name, slug, description, timestamp
		    	) VALUES (
		    	'{$active}', '{$name}', '{$slug}', '{$description}', '{$timestamp}'
		    	)";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
		    // success
		    if (isset($_GET['id'])) {
		    	$messagepass .= "The article category has been updated!";
		    } else {
		    	header('Location:article_categories.php');
		    }
		} else {
		    // failed
		    $messagefail = "There has been an error creating the article category!!";
		    $messagefail .= "<br />". mysql_error();
		}
		
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM categories WHERE id=" . $current;
		$result = mysql_query($query);
		$cat = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="name_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3><?php echo $article['name']; ?></h3>
		<?php } else { ?>
			<h3>Add Article Category</h3>
		<?php } ?>
	
		<div class="add_button_holder"><a href="article_categories.php"> &laquo; Back to Articles Categories</a></div>
	</div>
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	}
	if (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	?>
	<div id="content_holder">
	<div id="inner_left_column">

		<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$_GET['id']}"; }?>" method="post" enctype="multipart/form-data" >
		
			<div class="grey_contain">
				<ul>
					<li>
					<?php if ( $cat['active'] == 1 ) { $active_checked = "checked"; } ?>
						<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
					</li>
		       	</ul>    
			</div>
		
			<div class="form100">
				<label for="name">Category Name</label>
				<input type="text" name="name" id="name" value="<?php if (isset($_GET['id'])) { echo $cat['name']; } else { echo $_POST['name']; }; ?>" class="field100" tabindex="1" />
			</div>
		
			<div class="form_row">
				Description<br />
				<textarea name="description" id="description" tabindex="4"><?php if (isset($_GET['id'])) { echo $cat['description']; } else { echo $_POST['description']; }; ?></textarea>
				<script>
					CKEDITOR.replace( 'description',
	   				 {
	   				     toolbar : 'Base'
	   				 });
				</script>
			</div>
			
			<div class="form_button_row">
				<input type="submit" name="submit" value="Update Category" class="form_button" />
			</div>
	
		</form>
	</div>
	</div> <!-- content_holder -->
	    		
<?php include('includes/footer.php'); ?>