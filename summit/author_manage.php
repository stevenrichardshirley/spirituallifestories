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
	include "wideimage/WideImage.php";
	$dbc = new db();
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM categories WHERE id=" . $current;
		$result = mysql_query($query);
		$author = mysql_fetch_array($result);
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
				$file_name = $_FILES['uploaded']['name'];
				
				$ext = substr($file_name, -4);
				
				$title = $_POST['first_name'] . ' ' . $_POST['last_name'] ;
		
				$title = preg_replace("/(.*?)([A-Za-z0-9\s]*)(.*?)/", "$2", $title);
				$title = preg_replace('/\%/',' percent',$title); 
				$title = preg_replace('/\@/',' at ',$title); 
				$title = preg_replace('/\&/',' and ',$title); 
				$title = preg_replace('/\s[\s]+/','-',$title);    // Strip off multiple spaces 
				$title = preg_replace('/[\s\W]+/','-',$title);    // Strip off spaces and non-alpha-numeric 
				$title = preg_replace('/^[\-]+/','',$title); // Strip off the starting hyphens 
				$title = preg_replace('/[\-]+$/','',$title); // // Strip off the ending hyphens 
				
				$title = strtolower($title); 
			
				// trim and lowercase
				$title = strtolower(trim($title, '-'));
				$file_name = $title . $ext;
					
				// Where are we uploading?
				$target = "{$_SERVER["DOCUMENT_ROOT"]}/media/authors/";
				
				// Make the full path
				$target = $target . $file_name;
								
				if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
					$messagepass = "The file has been uploaded as ".$file_name. "<br />";
					
				} else {
					$messagefail = "Sorry, there was a problem uploading your file.<br />";
				}
				
				$image = WideImage::load('../media/authors/'.$file_name);
				$resized = $image->resize(700, 500, 'outside', 'down');
				$save = $resized->saveToFile('../media/authors/'.$file_name);
		    	
		    	$image = WideImage::load('../media/authors/'.$file_name);
				$resized = $image->resize(170, 170, 'outside', 'down');
				$cropped = $resized->crop('center','center',170,170);
				$save = $cropped->saveToFile('../media/authors/thumbs/'.$file_name);
			}
		}
				
		if ($file_name != '') {
			$image_url = $file_name;
		} else {
			$image_url = $author['image_url'];
		}
	
		if ($_POST['active'] == 'yes') {
			$active = 1;
		} else {
			$active = 0;
		}
	
		$first_name = mysql_clean($_POST['first_name']);
			$last_name = mysql_clean($_POST['last_name']);
			$email = mysql_clean($_POST['email']);
			$twitter = mysql_clean($_POST['twitter']);
			$facebook = mysql_clean($_POST['facebook']);
			$bio = mysql_clean($_POST['bio']);
			$timestamp = date("Y-m-d, g:i:s");
			          	
			
			if (isset($_GET['id'])) {
				$id = mysql_clean($_GET['id']);
			
				$query = "UPDATE authors SET
						first_name = '{$first_name}',
						last_name = '{$last_name}',
						email = '{$email}',
						twitter = '{$twitter}',
						facebook = '{$facebook}',
						bio = '{$bio}',
						image_url = '{$image_url}',
						timestamp = '{$timestamp}'
						WHERE id = {$id}";				

				
			} else {
			
			$query = "INSERT INTO authors (
			    first_name, last_name, email, twitter, facebook, bio, image_url, timestamp
			    ) VALUES (
			    '{$first_name}', '{$last_name}', '{$email}', '{$twitter}', '{$facebook}', '{$bio}', '{$image_url}', '{$timestamp}'
			    )";
			}
			
			$result = mysql_query($query);
			
			if (mysql_affected_rows() == 1) {
				// success
				if (isset($_GET['id'])) {
					$messagepass .= "The author has been updated!";
				} else {
					header('Location:authors.php');
				}
			} else {
				// failed
				$messagefail = "There has been an error modifying the author. Please contact the system admin or try again.";
				$messagefail .= "<br />". mysql_error();
				$messagefail .= $query;
			}
			
		
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM authors WHERE id=" . $current;
		$result = mysql_query($query);
		$author = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3><?php echo $author['first_name'] .' '. $author['last_name']; ?></h3>
		<?php } else { ?>
			<h3>Add Author</h3>
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
					<?php if ( $author['active'] == 1 ) { $active_checked = "checked"; } ?>
						<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
					</li>
		       	</ul>    
			</div>
		
			<div class="form50">
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" id="first_name" value="<?php if (isset($_GET['id'])) { echo $author['first_name']; } else { echo $_POST['first_name']; }; ?>" class="field100" tabindex="1" />
			</div>
			
			<div class="form50">
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" id="last_name" value="<?php if (isset($_GET['id'])) { echo $author['last_name']; } else { echo $_POST['last_name']; }; ?>" class="field100" tabindex="1" />
			</div>
			
			<div class="form100">
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="<?php if (isset($_GET['id'])) { echo $author['email']; } else { echo $_POST['email']; }; ?>" class="field100" tabindex="1" />
			</div>
			
			<div class="form50">
				<label for="facebook">Facebook</label>
				<input type="text" name="facebook" id="facebook" value="<?php if (isset($_GET['id'])) { echo $author['facebook']; } else { echo $_POST['facebook']; }; ?>" class="field100" tabindex="1" />
			</div>
			
			<div class="form50">
				<label for="twitter">Twitter</label>
				<input type="text" name="twitter" id="twitter" value="<?php if (isset($_GET['id'])) { echo $author['twitter']; } else { echo $_POST['twitter']; }; ?>" class="field100" tabindex="1" />
			</div>
		
			<div class="form_row">
				Author Bio<br />
				<textarea name="bio" id="bio" tabindex="4"><?php if (isset($_GET['id'])) { echo $author['bio']; } else { echo $_POST['bio']; }; ?></textarea>
				<script>
					CKEDITOR.replace( 'bio',
	   				 {
	   				     toolbar : 'Base'
	   				 });
				</script>
			</div>
			
			<div class="form_button_row">
				<input type="submit" name="submit" value="Update Category" class="form_button" />
			</div>
			
		</div> <!-- inner_left_column -->
	
		<div id="inner_right_column">
			<h4>Author Image</h4>
			<div class="form_row">
			<?php
	        if ($author['image_url'] != NULL) {
	            echo "<div class=\"form_row\">Current Image:<br /><img src=\"/media/authors/thumbs/{$author['image_url']}\"/></div>";
	        }
	        ?>
	      	<input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="6" />
			</div>
		
		</div>
	
		</form>
	</div> <!-- content_holder -->
	    		
<?php include('includes/footer.php'); ?>