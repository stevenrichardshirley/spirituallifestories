<? 
session_start();
if ($_GET['logout'] == 'true' ) {
	session_destroy();
} else {
	if(isset( $_SESSION['myusername'] )){
		header("location: ./home.php");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Login to Manage</title>
<link href="adminstyles.css" rel="stylesheet" type="text/css" />
</head>

<body>

<div id="login_holder">
	<div id="login_logo"><h1>Summit Content Management</h1></div>
	<?php 
	    if ( $_GET['msg'] )
	    {
	    	echo "<div id=\"login_message\">" . $_GET['msg'] . "</div>";
	    } else {
	    	echo "<div id=\"login_message\">Login to Manage</div><br /><br />";
	    }
	 ?>
	<div id="login_box">
		<form action="checklogin.php" method="post">
			<div class="form_row">
			<label for="myusername">Username</label>
			<input name="myusername" type="text" id="myusername" class="input_style" />
			</div>
			
			<div class="form_row">
			<label for="mypassword">Password</label>
			<input name="mypassword" type="password" id="mypassword" class="input_style" />
			</div>
			
			<div class="form_row">
			<input type="submit" name="Submit" value="Login" class="form_button" />
			</div>
		</form>
	</div>
</div>


</body>
</html>