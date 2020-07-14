<?
session_start();

if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
}

?>
<?php
	require_once '../app/bootstrap.php';
	require_once 'includes/functions.php';
	$dbc = new db();
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM home_banners WHERE id=" . $current;
		$result = mysql_query($query);
		$banner = mysql_fetch_array($result);
	}
	
		
	if (isset($_POST['submit'])) {
		$target1 = "{$_SERVER["DOCUMENT_ROOT"]}/media/headers/"; 
		$target1 = $target1 . basename( $_FILES['uploaded']['name']) ;
		$file = basename( $_FILES['uploaded']['name']);
		$ok=1; 
		
		//This is our size condition 
		if ($uploaded_size > 350000) 
		{ 
		    echo "Your file is too large.<br>"; 
		    $ok=0; 
		} 
		
		//This is our limit file type condition 
		if ($uploaded_type =="text/php") 
		{ 
		    echo "No PHP files<br>"; 
		    $ok=0; 
		} 
		
		
		//If everything is ok we try to upload it 
		else 
		{ 
		    if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target1)) 
		    { 
		    	$messagepass = "The file ". $file. " has been uploaded<br /><br />";
		    	$image_url = $file;
		    } else {
		    	$image_url = $banner['image_url'];
		    }
		}
			if ($_POST['active'] == 'yes') {
				$active = 1;
			} else {
				$active = 0;
			}
			
			if ($_POST['featured'] == 'yes') {
				$featured = 1;
			} else {
				$featured = 0;
			}
			
			$title = mysql_clean($_POST['title']);
			$link = mysql_clean($_POST['link']);
			$timestamp = date("Y-m-d, g:i:s");
			          	
			
			if (isset($_GET['id'])) {
				$id = mysql_clean($_GET['id']);
			
				$query = "UPDATE home_banners SET
						active = '{$active}',
						featured = '{$featured}',
						title = '{$title}',
						link = '{$link}',
						image_url = '{$image_url}',
						timestamp = '{$timestamp}'
						WHERE id = {$id}";				

				
			} else {
			
			$query = "INSERT INTO home_banners (
			    active, featured, title, link, image_url, timestamp
			    ) VALUES (
			    '{$active}', '{$featured}', '{$title}', '{$link}', '{$image_url}', '{$timestamp}'
			    )";
			}
			
			$result = mysql_query($query);
			
			if (mysql_affected_rows() == 1) {
				// success
				if (isset($_GET['id'])) {
					$messagepass .= "The banner has been updated!";
				} else {
					header('Location:banners.php');
				}
			} else {
				// failed
				$messagefail = "There has been an error modifying the banner. Please contact the system admin or try again.";
				$messagefail .= "<br />". mysql_error();
				$messagefail .= $query;
			}
			
		
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM home_banners WHERE id=" . $current;
		$result = mysql_query($query);
		$banner = mysql_fetch_array($result);
	}
	
?>

<?php include('includes/header.php'); ?>

	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
		<h3><?php echo $banner['title']; ?></h3>
		<?php } else { ?>
		<h3>Add Banner</h3>
		<?php } ?>
		<div class="add_button_holder"><a href="banners.php"> &laquo; Back to Banners</a></div>	
	</div>
	<div id="content_holder">
	<div class="innerpad">
	
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	} elseif (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	?>

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$current}"; }?>" method="post" enctype="multipart/form-data" >
	
	<div class="grey_contain">
		<ul>
			<li>
			<?php if ( $banner['active'] == 1 ) { $active_checked = "checked"; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>
			<li>
			<?php if ( $banner['featured'] == 1 ) { $featured = "checked"; } ?>
	       	    <input type="checkbox" name="featured" value="yes" <?php echo $featured; ?> />&nbsp;&nbsp;Featured?
	       	</li>
       	</ul>    
	</div>
	
	<div class="form50">
		<label for="title">Name:</label>
		<input type="text" name="title" id="title" value="<?php echo $banner['title']; ?>" class="field50" tabindex="1" />
	</div>
	
	<div class="form50">
		<label for="link">Link:</label>
		<input type="text" name="link" id="link" value="<?php echo $banner['link']; ?>" class="field50" tabindex="1" />
	</div>	
	
	<div class="form100">
		Banner:<br />
		<input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="3" />
	</div>
	
	<?php
        if ($banner['image_url'] != NULL) {
            echo "<div class=\"form_row\">Current Banner:<br /><img src=\"/media/headers/{$banner['image_url']}\" width=\"900\" /></div>";
        }
    ?>
		
	
	<div class="form_button_row">
	    <?php
	        if(isset($_GET['id'])) {
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Update Banner\" class=\"form_button\" />";
	        } else {
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Create Banner\" class=\"form_button\" />";
	        }
	        
	    ?>
	</div>
	
	</form>
	
	</div>
	</div>
	    		
<?php include('includes/footer.php'); ?>