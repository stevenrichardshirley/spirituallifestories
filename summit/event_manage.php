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
		$query = "SELECT * FROM events WHERE id=" . $current;
		$result = mysql_query($query);
		$event = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
	
		if ($_POST['active'] == 'yes') {
			$active = 1;
		} else {
			$active = 0;
		}
	
		$timestamp = date("Y-m-d, G:i:s");
		$location_id = mysql_clean($_POST['location_id']);
		$title = mysql_clean($_POST['title']);
		$slug = slug($title);
		$date = date("Y-m-d G:i:s", strtotime($_POST['date']));
		$description = mysql_clean($_POST['description']);
		$link = mysql_clean($_POST['link']);
		
		if (isset($_GET['id'])) {
		    $id = mysql_clean($_GET['id']);
		
		    $query = "UPDATE events SET
		    		active = '{$active}',
		    		location_id = '{$location_id}', 
		    		title = '{$title}',
		    		slug = '{$slug}',
		    		date = '{$date}',
		    		description = '{$description}',
		    		link = '{$link}',
		    		timestamp = '{$timestamp}'
		    	WHERE id = {$id}";

		    
		} else {
		
		    $query = "INSERT INTO events (
		    	active, location_id, title, slug, date, description, link, timestamp
		    	) VALUES (
		    	'{$active}', '{$location_id}', '{$title}', '{$slug}', '{$date}', '{$description}', '{$link}', '{$timestamp}'
		    	)";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
		    // success
		    if (isset($_GET['id'])) {
		    	$messagepass .= "The event has been updated!";
		    } else {
		    	header('Location:events.php');
		    }
		} else {
		    // failed
		    $messagefail = "There has been an error creating the event!!";
		    $messagefail .= "<br />". mysql_error();
		}
		
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM events WHERE id=" . $current;
		$result = mysql_query($query);
		$event = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
			<h3><?php echo $event['title']; ?></h3>
		<?php } else { ?>
			<h3>Add Event</h3>
		<?php } ?>
	
		<div class="add_button_holder"><a href="events.php"> &laquo; Back to Events</a></div>
	</div>
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	} elseif (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	?>
	<div id="content_holder">
	<div class="innerpad">

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$_GET['id']}"; }?>" method="post" enctype="multipart/form-data" >
	
		<div class="grey_contain">
			<ul>
			<li>
			<?php if ( $event['active'] == 1 ) { $active_checked = "checked"; } ?>
				<input type="checkbox" name="active" value="yes" <?php echo $active_checked ?> />&nbsp;&nbsp;Active?&nbsp;&nbsp;
			</li>

	       	<li>    
		       	Location:
		       	<select name="location_id">
		       	<?php
		       	$locations = admin::locations();
		       	foreach ( $locations as $location ) {
		       	?>
		       		<option value="<?php echo $location->id; ?>" <?php if ( $event['location_id'] == $location->id ) { echo 'selected';} ?>><?php echo $location->title; ?></option> 
		       	<?php } ?>
		       	</select>
		    </li>
	       	</ul>    
		</div>
	
		<div class="form100">
			<label for="title">Event Title</label>
			<input type="text" name="title" id="title" value="<?php if (isset($_GET['id'])) { echo $event['title']; } else { echo $_POST['title']; }; ?>" class="field100" tabindex="1" />
		</div>
		
		
		<div class="form_row">
		<div class="form50">
			<label for="date">Date</label>
			<input type="text" name="date" id="date" value="<?php if (isset($_GET['id'])) { echo date("m/d/y, g:ia", strtotime($event['date'])); } else { echo $_POST['date']; }; ?>" class="field50" tabindex="2" />
		</div>
		
		<div class="form50">
			<label for="link">Link</label>
			<input type="text" name="link" id="link" value="<?php if (isset($_GET['id'])) { echo $event['link']; } else { echo $_POST['link']; }; ?>" class="field50" tabindex="3" />
		</div>
		</div>
		
		<div class="form_row">
			Description<br />
			<textarea name="description" id="description" tabindex="4"><?php if (isset($_GET['id'])) { echo $event['description']; } else { echo $_POST['description']; }; ?></textarea>
			<script>
				CKEDITOR.replace( 'description',
   				 {
   				     toolbar : 'Base'
   				 });
			</script>
		</div>
		
		<div class="form_button_row">
		<?php  if(isset($_GET['id'])) { ?>
			<input type="submit" name="submit" value="Update Event" class="form_button" />
		<?php } else { ?>
			<input type="submit" name="submit" value="Create Event" class="form_button" />
		<?php } ?>
		</div>
	</div> <!-- inner_left_column -->

	</form>
	
	</div> <!-- content_holder -->
	    		
<?php include('includes/footer.php'); ?>