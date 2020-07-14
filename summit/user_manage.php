<? 
session_start();
if ($_GET['logout'] == 'true' ) {
	session_destroy();
} else {
	if(!isset( $_SESSION['myusername'] )){
	header("location: ./home.php");
	}
}

?>
<?php
	require_once 'includes/db_conn.php';
	require_once 'includes/functions.php';
	$dbc = new db();
	
	if (isset($_POST['submit'])) {
	
		$query = "SELECT slug FROM church_data WHERE state_id=" . $_POST['state_id'];
		$result = mysql_query($query);
		$church_data = mysql_fetch_array($result);
	
		$church_name = $church_data['slug'];	
		$state_id = mysql_clean($_POST['state_id']);
		$username = mysql_clean($_POST['username']);
		$email = mysql_clean($_POST['email']);
		$password = sha1($_POST['password']);
		
		if (isset($_GET['id'])) {
		    $id = mysql_clean($_GET['id']);
		    
		    if ( $_POST['password'] != '' ) {
		
		    $query = "UPDATE users SET
		    		state_id = '{$state_id}',
		    		church_name = '{$church_name}',
		    		username = '{$username}',
		    		email = '{$email}',
		    		password = '{$password}'
		    	WHERE user_id = {$id}";
		    	
		    } else {
		    
		    $query = "UPDATE users SET
		    		state_id = '{$state_id}',
		    		church_name = '{$church_name}',
		    		username = '{$username}',
		    		email = '{$email}'
		    	WHERE user_id = {$id}";
		    
		    }

		    
		} else {
		
		    $query = "INSERT INTO users (
		    	state_id, church_name, username, email, password
		    	) VALUES (
		    	'{$state_id}', '{$church_name}', '{$username}', '{$email}', '{$password}'
		    	)";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
		    // success
		    if (isset($_GET['id'])) {
		    	$messagepass .= "The user has been updated!";
		    } else {
		    	header('Location:users.php');
		    }
		} else {
		    // failed
		    $messagefail .= "<br />". mysql_error();
		}
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM users WHERE user_id=" . $current. " LIMIT 1";
		$result = mysql_query($query);
		$user_info = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
			
	<a href="users.php">Return to Previous Page</a><br /><br/>
	<?php if (isset($_GET['id'])) { ?>
	<h2>Edit User</h2><br />
	<?php } else { ?>
	<h2>Add User</h2>
	<?php } ?>
	
	<?php 
	if(!empty($messagepass)) {
	    echo "<div class=\"messagepass\">{$messagepass}</div>";
	} elseif (!empty($messagefail)) {
	    echo "<div class=\"messagefail\">{$messagefail}</div>";
	}
	
	if(!empty($errors)) {
	    echo "<div class=\"messagewarning\">Please review the following fields: <br /> ";
	    foreach($errors as $error) {
	    	echo "<strong>{$error}</strong> | ";
	    }
	    echo "</div>";
	}
	?>

	<form action="<?= $_SERVER['PHP_SELF'] ?><?php if (isset($_GET['id'])) { echo "?id={$_GET['id']}"; }?>" method="post" enctype="multipart/form-data" >
	
		<div class="form_row">
			<label for="state_id">Church</label>
			<select name="state_id" id="state_id">
				<?php
				$result = mysql_query("SELECT state_id, name FROM church_data ORDER BY name");
				while ( $row = mysql_fetch_array($result) )
				{
				?>
				<option value="<?php echo $row['state_id']; ?>" <?php if ( isset($_GET['id']) ) { if ( $row['state_id'] == $user_info['state_id'] ) { echo "selected"; } }?>>
					<?php echo $row['name']; ?>
				</option>
				<?php
				}
				?>
				
			</select>
		</div>
	
		<div class="form_row">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" value="<?php echo $user_info['username']; ?>" class="inputStyle" tabindex="1" />
		</div>
		
		<div class="form_row">
			<label for="email">Email</label>
			<input type="text" name="email" id="email" value="<?php echo $user_info['email']; ?>" class="inputStyle" tabindex="2" />
		</div>
		
		<div class="form_row">
			<label for="password">Enter New Password <small>( If you enter a new password in this blank, it will replace the password for this user )</small></label>
			<input type="text" name="password" id="password" value="" class="inputStyle" tabindex="3" />
		</div>
				
		<div class="form_row">
			<input type="submit" name="submit" value="Update User" class="formButton" />
		</div>

	</form>
	    		
<?php include('includes/footer.php'); ?>