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
		$query = "SELECT * FROM locations WHERE id=" . $current;
		$result = mysql_query($query);
		$location = mysql_fetch_array($result);
	}
	
	if (isset($_POST['submit'])) {
	
		$timestamp = date("Y-m-d, g:i:s");
		$title = mysql_clean($_POST['title']);
		$address = mysql_clean($_POST['address']);
		$suite = mysql_clean($_POST['suite']);
		$city = mysql_clean($_POST['city']);
		$state = mysql_clean($_POST['state']);
		$zip_code = mysql_clean($_POST['zip_code']);
		$google_map = mysql_clean($_POST['google_map']);
		$open_date = date("Y-m-d G:i:s", strtotime($_POST['open_date']));
		$hours = mysql_clean($_POST['hours']);
		$phone = mysql_clean($_POST['phone']);
		$email = mysql_clean($_POST['email']);
		
		if (isset($_GET['id'])) {
		    $id = mysql_clean($_GET['id']);
		
		    $query = "UPDATE locations SET
		    		title = '{$title}',
		    		address = '{$address}',
		    		suite = '{$suite}',
		    		city = '{$city}',
		    		state = '{$state}',
		    		zip_code = '{$zip_code}',
		    		google_map = '{$google_map}',
		    		open_date = '{$open_date}',
		    		hours = '{$hours}',
		    		phone = '{$phone}',
		    		email = '{$email}',
		    		timestamp = '{$timestamp}'
		    	WHERE id = {$id}";

		    
		} else {
		
		    $query = "INSERT INTO locations (
		    	title, address, suite, city, state, zip_code, google_map, open_date, hours, phone, email, timestamp
		    	) VALUES (
		    	'{$title}', '{$address}', '{$suite}', '{$city}', '{$state}', '{$zip_code}', '{$google_map}', '{$phone}', '{$open_date}', '{$hours}', '{$email}', '{$timestamp}'
		    	)";
		}
		
		$result = mysql_query($query);
		
		if (mysql_affected_rows() == 1) {
		    // success
		    if (isset($_GET['id'])) {
		    	$messagepass .= "The location has been updated!";
		    } else {
		    	header('Location:locations.php');
		    }
		} else {
		    // failed
		    $messagefail = "There has been an error creating the location!!";
		    $messagefail .= "<br />". mysql_error();
		}
		
					
	} // end if (isset($_POST['submit']))
	
	if (isset($_GET['id'])) {
		$current = $_GET['id'];
		$query = "SELECT * FROM locations WHERE id=" . $current;
		$result = mysql_query($query);
		$location = mysql_fetch_array($result);
	}

?>

<?php include('includes/header.php'); ?>
	
	<div id="title_bar">
		<?php if (isset($_GET['id'])) { ?>
		<h3><?php echo $location['title']; ?></h3>
		<?php } else { ?>
		<h3>Add Location</h3>
		<?php } ?>
		<div class="add_button_holder"><a href="locations.php"> &laquo; Back to Locations</a></div>	
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
	
		<div class="form100">
			<label for="title">Location Name</label>
			<input type="text" name="title" id="title" value="<?php if (isset($_GET['id'])) { echo $location['title']; } else { echo $_POST['title']; }; ?>" class="field100" tabindex="1" />
		</div>

		<div class="form_row">
		<div class="form50">
			<label for="address">Address</label>
			<input type="text" name="address" id="address" value="<?php if (isset($_GET['id'])) { echo $location['address']; } else { echo $_POST['address']; }; ?>" class="field50" tabindex="2" />
		</div>
		
		<div class="form50">
			<label for="suite">Suite</label>
			<input type="text" name="suite" id="suite" value="<?php if (isset($_GET['id'])) { echo $location['suite']; } else { echo $_POST['suite']; }; ?>" class="field50" tabindex="3" />
		</div>
		</div>
		
		<div class="form_row">
		<div class="form30">
			<label for="city">City</label>
			<input type="text" name="city" id="city" value="<?php if (isset($_GET['id'])) { echo $location['city']; } else { echo $_POST['city']; }; ?>" class="field30" tabindex="4" />
		</div>
		
		<div class="form30">
			<label for="state">State</label>
			<select name="state"> 
				<option value="" selected="selected">Select a State</option> 
				<option value="AL" <?php if ($location['state'] == 'AL') { echo 'selected';} ?>>Alabama</option> 
				<option value="AK" <?php if ($location['state'] == 'AK') { echo 'selected';} ?>>Alaska</option> 
				<option value="AZ" <?php if ($location['state'] == 'AZ') { echo 'selected';} ?>>Arizona</option> 
				<option value="AR" <?php if ($location['state'] == 'AR') { echo 'selected';} ?>>Arkansas</option> 
				<option value="CA" <?php if ($location['state'] == 'CA') { echo 'selected';} ?>>California</option> 
				<option value="CO" <?php if ($location['state'] == 'CO') { echo 'selected';} ?>>Colorado</option> 
				<option value="CT" <?php if ($location['state'] == 'CT') { echo 'selected';} ?>>Connecticut</option> 
				<option value="DE" <?php if ($location['state'] == 'DE') { echo 'selected';} ?>>Delaware</option> 
				<option value="DC" <?php if ($location['state'] == 'DC') { echo 'selected';} ?>>District Of Columbia</option> 
				<option value="FL" <?php if ($location['state'] == 'FL') { echo 'selected';} ?>>Florida</option> 
				<option value="GA" <?php if ($location['state'] == 'GA') { echo 'selected';} ?>>Georgia</option> 
				<option value="HI" <?php if ($location['state'] == 'HI') { echo 'selected';} ?>>Hawaii</option> 
				<option value="ID" <?php if ($location['state'] == 'ID') { echo 'selected';} ?>>Idaho</option> 
				<option value="IL" <?php if ($location['state'] == 'IL') { echo 'selected';} ?>>Illinois</option> 
				<option value="IN" <?php if ($location['state'] == 'IN') { echo 'selected';} ?>>Indiana</option> 
				<option value="IA" <?php if ($location['state'] == 'IA') { echo 'selected';} ?>>Iowa</option> 
				<option value="KS" <?php if ($location['state'] == 'KS') { echo 'selected';} ?>>Kansas</option> 
				<option value="KY" <?php if ($location['state'] == 'KY') { echo 'selected';} ?>>Kentucky</option> 
				<option value="LA" <?php if ($location['state'] == 'LA') { echo 'selected';} ?>>Louisiana</option> 
				<option value="ME" <?php if ($location['state'] == 'ME') { echo 'selected';} ?>>Maine</option> 
				<option value="MD" <?php if ($location['state'] == 'MD') { echo 'selected';} ?>>Maryland</option> 
				<option value="MA" <?php if ($location['state'] == 'MA') { echo 'selected';} ?>>Massachusetts</option> 
				<option value="MI" <?php if ($location['state'] == 'MI') { echo 'selected';} ?>>Michigan</option> 
				<option value="MN" <?php if ($location['state'] == 'MN') { echo 'selected';} ?>>Minnesota</option> 
				<option value="MS" <?php if ($location['state'] == 'MS') { echo 'selected';} ?>>Mississippi</option> 
				<option value="MO" <?php if ($location['state'] == 'MO') { echo 'selected';} ?>>Missouri</option> 
				<option value="MT" <?php if ($location['state'] == 'MT') { echo 'selected';} ?>>Montana</option> 
				<option value="NE" <?php if ($location['state'] == 'NE') { echo 'selected';} ?>>Nebraska</option> 
				<option value="NV" <?php if ($location['state'] == 'NV') { echo 'selected';} ?>>Nevada</option> 
				<option value="NH" <?php if ($location['state'] == 'NH') { echo 'selected';} ?>>New Hampshire</option> 
				<option value="NJ" <?php if ($location['state'] == 'NJ') { echo 'selected';} ?>>New Jersey</option> 
				<option value="NM" <?php if ($location['state'] == 'NM') { echo 'selected';} ?>>New Mexico</option> 
				<option value="NY" <?php if ($location['state'] == 'NY') { echo 'selected';} ?>>New York</option> 
				<option value="NC" <?php if ($location['state'] == 'NC') { echo 'selected';} ?>>North Carolina</option> 
				<option value="ND" <?php if ($location['state'] == 'ND') { echo 'selected';} ?>>North Dakota</option> 
				<option value="OH" <?php if ($location['state'] == 'OH') { echo 'selected';} ?>>Ohio</option> 
				<option value="OK" <?php if ($location['state'] == 'OK') { echo 'selected';} ?>>Oklahoma</option> 
				<option value="OR" <?php if ($location['state'] == 'OR') { echo 'selected';} ?>>Oregon</option> 
				<option value="PA" <?php if ($location['state'] == 'PA') { echo 'selected';} ?>>Pennsylvania</option> 
				<option value="RI" <?php if ($location['state'] == 'RI') { echo 'selected';} ?>>Rhode Island</option> 
				<option value="SC" <?php if ($location['state'] == 'SC') { echo 'selected';} ?>>South Carolina</option> 
				<option value="SD" <?php if ($location['state'] == 'SD') { echo 'selected';} ?>>South Dakota</option> 
				<option value="TN" <?php if ($location['state'] == 'TN') { echo 'selected';} ?>>Tennessee</option> 
				<option value="TX" <?php if ($location['state'] == 'TX') { echo 'selected';} ?>>Texas</option> 
				<option value="UT" <?php if ($location['state'] == 'UT') { echo 'selected';} ?>>Utah</option> 
				<option value="VT" <?php if ($location['state'] == 'VT') { echo 'selected';} ?>>Vermont</option> 
				<option value="VA" <?php if ($location['state'] == 'VA') { echo 'selected';} ?>>Virginia</option> 
				<option value="WA" <?php if ($location['state'] == 'WA') { echo 'selected';} ?>>Washington</option> 
				<option value="WV" <?php if ($location['state'] == 'WV') { echo 'selected';} ?>>West Virginia</option> 
				<option value="WI" <?php if ($location['state'] == 'WI') { echo 'selected';} ?>>Wisconsin</option> 
				<option value="WY" <?php if ($location['state'] == 'WY') { echo 'selected';} ?>>Wyoming</option>
			</select>
		</div>
		
		<div class="form30">
			<label for="zip_code">Zip Code</label>
			<input type="text" name="zip_code" id="zip_code" value="<?php if (isset($_GET['id'])) { echo $location['zip_code']; } else { echo $_POST['zip_code']; }; ?>" class="field30" tabindex="6" />
		</div>
		
		</div>
		
		<div class="form_row">		
		<div class="form50">
			<label for="phone">Phone <small>(xxx-xxx-xxxx)</small></label>
			<input type="text" name="phone" id="phone" value="<?php if (isset($_GET['id'])) { echo $location['phone']; } else { echo $_POST['phone']; }; ?>" class="field50" tabindex="7" />
		</div>
		
		<div class="form50">
			<label for="email">Email</label>
			<input type="text" name="email" id="email" value="<?php if (isset($_GET['id'])) { echo $location['email']; } else { echo $_POST['email']; }; ?>" class="field50" tabindex="8" />
		</div>
		</div>
		
		<div class="form_button_row">
			<input type="submit" name="submit" value="Update Location" class="form_button" />
		</div>

	</div> <!-- inner_left_column -->
	
	<div id="inner_right_column">
	<h4>Location Details</h4>
		<div class="form_row">
			<label for="open_date">Opening Date</label>
			<input type="text" name="open_date" id="date" value="<?php if (isset($_GET['id'])) { echo date("m/d/y, g:ia", strtotime($location['open_date'])); } else { echo $_POST['open_date']; }; ?>" class="field100" tabindex="2" />
		</div>
		
		<div class="form_row">
			Hours of Operation<br />
			<textarea name="hours" id="hours" rows="5" tabindex="4"><?php if (isset($_GET['id'])) { echo $location['hours']; } else { echo $_POST['hours']; }; ?></textarea>
		</div>
		
		<div class="form_row">
			Embed code for Google Map<br />
			<textarea name="google_map" id="google_map" rows="6" tabindex="4"><?php if (isset($_GET['id'])) { echo $location['google_map']; } else { echo $_POST['google_map']; }; ?></textarea>
		</div>
		
	</div>

	</form>
	
	</div>
	</div>
	    		
<?php include('includes/footer.php'); ?>