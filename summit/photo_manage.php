<?
session_start();

if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
}

?>
<?php
	require_once '../app/bootstrap.php';
	require_once 'includes/functions.php';
	include "wideimage/WideImage.php";
	$dbc = new db();
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM photos WHERE id=" . $current;
		$result = mysql_query($query);
		$photo = mysql_fetch_array($result);
	}
	
		
	if (isset($_POST['submit'])) {
			
		if ( $HTTP_POST_FILES['uploaded']['size'] != '0' ) {
		
			$limit = 3500000;
			$file_size = $HTTP_POST_FILES['uploaded']['size'];
			
			if ( $file_size >= $limit )
			{ 
			    $messagefail = "Your file is too large. Please scale down the photo and try uploading again.<br />"; 
			     
			} else {
				// Take the file name and clean it up a bit
				$file_name = slug_file( $_FILES['uploaded']['name'] ) ;
					
				// Where are we uploading?
				$target = "{$_SERVER["DOCUMENT_ROOT"]}/media/photos/";
				
				// Make the full path
				$target = $target . $file_name; 
				
				if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
					$messagepass = "The file has been uploaded as ".$file_name. "<br />";
					
				} else {
					$messagefail = "Sorry, there was a problem uploading your file.<br />";
				}
				
				$image = WideImage::load('../media/photos/'.$file_name);
				$resized = $image->resize(700, 500, 'outside');
				$save = $resized->saveToFile('../media/photos/'.$file_name);
		    	
		    	$image = WideImage::load('../media/photos/'.$file_name);
				$resized = $image->resize(220, 142, 'outside');
				$cropped = $resized->crop(0,0,220,142);
				$save = $cropped->saveToFile('../media/photos/thumbs/'.$file_name);
			}
		}
				
		if ($file_name != '') {
			$image_url = $file_name;
		} else {
			$image_url = $photo['image_url'];
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
		$description = mysql_clean($_POST['description']);
		$location_id = mysql_clean($_POST['location_id']);
		$timestamp = date("Y-m-d G:i:s");
		          	
		
		if (isset($_GET['id'])) {
			$id = mysql_clean($_GET['id']);
		
			$query = "UPDATE photos SET
					active = '{$active}',
					featured = '{$featured}',
					title = '{$title}',
					location_id = '{$location_id}',
					description = '{$description}',
					image_url = '{$image_url}',
					timestamp = '{$timestamp}'
					WHERE id = {$id}";				

			
		} else {
		
		$query = "INSERT INTO photos (
		    active, featured, title, location_id, description, image_url, timestamp
		    ) VALUES (
		    '{$active}', '{$featured}', '{$title}', '{$location_id}', '{$description}', '{$image_url}', '{$timestamp}'
		    )";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
			// success
			if (isset($_GET['id'])) {
				$messagepass .= "The photo has been updated!";
			} else {
				header('Location:photos.php');
			}
		} else {
			// failed
			$messagefail = "There has been an error modifying the photo. Please contact the system admin or try again.";
			$messagefail .= "<br />". mysql_error();
			$messagefail .= $query;
		}
			
		
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM photos WHERE id=" . $current;
		$result = mysql_query($query);
		$photo = mysql_fetch_array($result);
	}
	
?>

<?php include('includes/header.php'); ?>

	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
		<h3><?php echo $photo['title']; ?></h3>
		<?php } else { ?>
		<h3>Add Photo</h3>
		<?php } ?>
		<div class="add_button_holder"><a href="photos.php"> &laquo; Back to Photos</a></div>	
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

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$current}"; }?>" method="post" enctype="multipart/form-data" >
	
	<div class="grey_contain">
		<ul>
			<li>
			<?php if ( $photo['active'] == 1 ) { $active_checked = "checked"; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>
			<li>
			<?php if ( $photo['featured'] == 1 ) { $featured = "checked"; } ?>
	       	    <input type="checkbox" name="featured" value="yes" <?php echo $featured; ?> />&nbsp;&nbsp;Featured?
	       	</li>
	       	<li>    
		       	Location:
		       	<select name="location_id">
		       	<?php
		       	$locations = admin::locations();
		       	foreach ( $locations as $location ) {
		       	?>
		       		<option value="<?php echo $location->id; ?>" <?php if ( $photo['location_id'] == $location->id ) { echo 'selected';} ?>><?php echo $location->title; ?></option> 
		       	<?php } ?>
		       	</select>
		    </li>
       	</ul>    
	</div>
	
	<div class="form100">
		<label for="title">Name:</label>
		<input type="text" name="title" id="title" value="<?php echo $photo['title']; ?>" class="field100" tabindex="1" />
	</div>
	
	<div class="form_row">
		Caption<br />
		<textarea name="description" id="description" tabindex="2"><?php if (isset($_GET['id'])) { echo $photo['description']; } else { echo $_POST['description']; }; ?></textarea>
		<script>
			CKEDITOR.replace( 'description',
				 {
				     toolbar : 'Base'
				 });
		</script>
	</div>
	
	<div class="form_button_row">
	    <?php
	        if(isset($_GET['id'])) {
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Update Photo\" class=\"form_button\" />";
	        } else {
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Create Photo\" class=\"form_button\" />";
	        }
	        
	    ?>
	</div>
	
	</div> <!-- inner_left_column -->
	
	<div id="inner_right_column">
	<h4>Upload Photo</h4>
	<div class="form100">
		Upload New Image:<br />
		<input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="3" />
	</div>
	
	<?php
        if ($photo['image_url'] != NULL) {
            echo "<div class=\"form_row\">Current Image:<br /><img src=\"/media/photos/{$photo['image_url']}\" width=\"100%\" /></div>";
        }
    ?>
	
	</div> <!-- inner_right_column -->
	
	</form>
	
	</div>
	</div>
	    		
<?php include('includes/footer.php'); ?>