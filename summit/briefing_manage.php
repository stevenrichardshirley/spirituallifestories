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
		$query = "SELECT * FROM briefing WHERE id=" . $current;
		$result = mysql_query($query);
		$briefing = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
		$target1 = "{$_SERVER["DOCUMENT_ROOT"]}/newsletter/downloads/"; 
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
		    	$file = $file;
		    } else {
		    	$file = $briefing['file'];
		    }
		}

			$header = mysql_clean($_POST['header']);
			$footer = mysql_clean($_POST['footer']);
			$file_title = mysql_clean($_POST['file_title']);
			$date = date('Y-m-d', strtotime('last monday'));
			          	
			
			if (isset($_GET['id'])) {
				$id = mysql_clean($_GET['id']);
			
				$query = "UPDATE briefing SET
						header = '{$header}',
						footer = '{$footer}',
						file = '{$file}',
						file_title = '{$file_title}',
						date = '{$date}'
						WHERE id = {$id}";				
				
			} else {
			
			$query = "INSERT INTO briefing (
			    header, footer, file, file_title, date
			    ) VALUES (
			    '{$header}', '{$footer}', '{$file}', '{$file_title}', '{$date}'
			    )";
			}
			
			$result = mysql_query($query);
			
			if (mysql_affected_rows() == 1) {
				// success
				if (isset($_GET['id'])) {
					$messagepass .= "The briefing has been updated!";
				} else {
					header('Location:briefing.php');
				}
			} else {
				// failed
				$messagefail = "There has been an error modifying the briefing. Please contact the system admin or try again.";
				$messagefail .= "<br />". mysql_error();
				$messagefail .= $query;
			}
			
		
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM briefing WHERE id=" . $current;
		$result = mysql_query($query);
		$briefing = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3>Briefing</h3>
		<?php } else { ?>
			<h3>Add Briefing</h3>
		<?php } ?>
	
		<div class="add_button_holder"><a href="article_briefing.php"> &laquo; Back to Articles briefing</a></div>
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
		
			<div class="form_row">
			Header Content: <small>This is the paragraph that will go above the feed of articles</small><br />
			<textarea name="header" id="header" tabindex="5" rows="20"><?php echo $briefing['header']; ?></textarea>
			<script>
				var editor = CKEDITOR.replace( 'header',
   				 {
   				 	height: "250",
   				    toolbar : 'Base'
   				 });
			</script>
		</div>
		
		<div class="form_row">
			Footer Content: <small>This is the paragraph that will go under the list of articles for the week.</small><br />
			<textarea name="footer" id="footer" tabindex="5" rows="20"><?php echo $briefing['footer']; ?></textarea>
			<script>
				var editor = CKEDITOR.replace( 'footer',
   				 {
   				 	height: "250",
   				    toolbar : 'Base'
   				 });
			</script>
		</div>
			
		<div class="form_button_row">
			<input type="submit" name="submit" value="Update Briefing" class="form_button" />
		</div>
	
	</div>
	<div id="inner_right_column">
		<?php
	        if ($briefing['file'] != NULL) {
	            echo "<div class=\"form_row\"><strong>Current File:</strong><br /><a href=\"/newsletter/downloads/{$briefing['file']}\">{$briefing['file']}</a></div>";
	        }
	        ?>
	        <div class="form_row">
			File to Attach as link in Email<br />
	        <input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="6" />
			</div>
			
			<div class="form_row">
				<label for="file_title">Text for Link in Email:</label>
				<input type="text" name="file_title" id="file_title" value='<?php echo $briefing['file_title']; ?>' class="field100" tabindex="4" />
			</div>
				
		</div>
		
		</form>

	</div>
	</div> <!-- content_holder -->
	    		
<?php include('includes/footer.php'); ?>