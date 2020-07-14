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
		$query = "SELECT * FROM tools_of_the_trade WHERE id=" . $current;
		$result = mysql_query($query);
		$tool_of_trade = mysql_fetch_array($result);
	}
	
		
	if (isset($_POST['submit'])) {
		$target1 = "{$_SERVER["DOCUMENT_ROOT"]}/media/tools_of_the_trade/"; 
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
		    	$image_url = $tool_of_trade['image_url'];
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
			$slug = slug($title);
			$date = date("Y-m-d G:i:s", strtotime($_POST['date']));
			$description = mysql_clean($_POST['description']);
			$link = mysql_clean($_POST['link']);
			$timestamp = date("Y-m-d G:i:s");
			          	
			
			if (isset($_GET['id'])) {
				$id = mysql_clean($_GET['id']);
			
				$query = "UPDATE tools_of_the_trade SET
						active = '{$active}',
						featured = '{$featured}',
						title = '{$title}',
						slug = '{$slug}',
						date = '{$date}',
						link = '{$link}',
						description = '{$description}',
						image_url = '{$image_url}',
						timestamp = '{$timestamp}'
						WHERE id = {$id}";				

				
			} else {
			
			$query = "INSERT INTO tools_of_the_trade (
			    active, featured, title, slug, date, link, description, image_url, timestamp
			    ) VALUES (
			    '{$active}', '{$featured}', '{$title}', '{$slug}', '{$date}', '{$link}', '{$description}', '{$image_url}', '{$timestamp}'
			    )";
			}
			
			$result = mysql_query($query);
			
			if (mysql_affected_rows() == 1) {
				// success
				if (isset($_GET['id'])) {
					$messagepass .= "The tools of the trade has been updated!";
				} else {
					header('Location:tools_of_trade.php');
				}
			} else {
				// failed
				$messagefail = "There has been an error modifying the tool of the trade. Please contact the system admin or try again.";
				$messagefail .= "<br />". mysql_error();
				$messagefail .= $query;
			}
			
		
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM tools_of_the_trade WHERE id=" . $current;
		$result = mysql_query($query);
		$tool_of_trade = mysql_fetch_array($result);
	}
	
?>

<?php include('includes/header.php'); ?>

	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
		<h3><?php echo $tool_of_trade['title']; ?></h3>
		<?php } else { ?>
		<h3>Add Tool</h3>
		<?php } ?>
		<div class="add_button_holder"><a href="eye_candy.php"> &laquo; Back to Eye Candy</a></div>	
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

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$current}"; }?>" method="post" enctype="multipart/form-data" >
	
	<div class="grey_contain">
		<ul>
			<li>
			<?php if ( $tool_of_trade['active'] == 1 ) { $active_checked = "checked"; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>
			<li>
			<?php if ( $tool_of_trade['featured'] == 1 ) { $featured = "checked"; } ?>
	       	    <input type="checkbox" name="featured" value="yes" <?php echo $featured; ?> />&nbsp;&nbsp;Featured?
	       	</li>
       	</ul>    
	</div>
	
	<div class="form100">
		<label for="title">Name:</label>
		<input type="text" name="title" id="title" value="<?php echo $tool_of_trade['title']; ?>" class="field100" tabindex="1" />
	</div>
	
	<div class="form_row">
		<div class="form50">
			<label for="date">Date to Publish</label>
			<input type="text" name="date" id="date" value="<?php if (isset($_GET['id'])) { echo date("m/d/y, g:ia", strtotime($tool_of_trade['date'])); } else { echo $_POST['date']; }; ?>" class="field50" tabindex="2" />
		</div>
		
		<div class="form50">
			<label for="link">Link</label>
			<input type="text" name="link" id="link" value="<?php if (isset($_GET['id'])) { echo $tool_of_trade['link']; } else { echo $_POST['link']; }; ?>" class="field50" tabindex="3" />
		</div>
		</div>
	
	<div class="form_row">
		Description<br />
		<textarea name="description" id="description" tabindex="4"><?php if (isset($_GET['id'])) { echo $tool_of_trade['description']; } else { echo $_POST['description']; }; ?></textarea>
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
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Update Tool\" class=\"form_button\" />";
	        } else {
	        	echo "<input type=\"submit\" name=\"submit\" value=\"Create Tool\" class=\"form_button\" />";
	        }
	        
	    ?>
	</div>
	
	</div> <!-- inner_left_column -->
	
	<div id="inner_right_column">
	<h4>Upload Image for this Eye Candy</h4>
	<div class="form100">
		Upload New Image:<br />
		<input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="3" />
	</div>
	
	<?php
        if ($tool_of_trade['image_url'] != NULL) {
            echo "<div class=\"form_row\">Current Image:<br /><img src=\"/media/tools_of_the_trade/{$tool_of_trade['image_url']}\" width=\"100%\" /></div>";
        }
    ?>
	
	</div> <!-- inner_right_column -->
	
	</form>
	
	</div>
	</div>
	    		
<?php include('includes/footer.php'); ?>