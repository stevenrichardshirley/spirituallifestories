<?php
define('website_root_url', 'http://www.betabit.com.br/ls');

class email                        
{
	private static function setup_db() {
		$db = new db();
		$sql = "CREATE TABLE `user_books` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) DEFAULT NULL,
		  `title` varchar(255) DEFAULT NULL,
		  `slug` varchar(255) DEFAULT NULL,
		  `cover` int(11) DEFAULT NULL,
		  `image_url` varchar(255) DEFAULT NULL,
		  `description` text,
		  `timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$db->query( $sql );
	}
    
    private static function mymail($to, $subject, $message)
    {
        require_once 'phpmailer/class.phpmailer.php';
        $mm = new PHPMailer();
        $mm->IsSMTP();
        $list = explode(',', $to);
        foreach ($list as $ito)
            $mm->AddAddress($ito);
        $mm->Subject = $subject;
        $mm->MsgHTML($message);
        $mm->Send();
        return true;
    }
	
	public function password_reset($email, $token) {
		$db = new db();
		$to = $email;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: LifeStories.com <info@lifestories.com>' . "\r\n";
		// $headers .= 'Bcc: ben@benjordan.net' . "\r\n";
		$subject = 'Reset Password Request from LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>Reset your LifeStories.com Password</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 20px;">
		  <table style="background: #fff; border: 2px solid #d4d5d6; margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 26px;">
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" /><br />
		      <p id="title">Reset your password</p>
		      <p>A request has been submitted to recover a lost password from http://lifestories.com</p>
		      <p>To complete the password change, please visit the following URL and enter the requested info.</p>
		      <p id="message"><a href="'.website_root_url.'/forgot_password/token-' .$token. '">http://lifestories.com/forgot_password/token-' .$token. '</a></strong></p>
		      <hr />
		      <p>If you did not specifically request this password change, please disregard this notice.</p>
		      </td>
		    </tr>
		  </table>
		</div>
		</body>
		</html>
		';
		
		// Send some mail sir
		return email::mymail($to, $subject, $message);
	}
	
	public function contact_us($email) {
		$to = "chad@lifestories.com";
		// $to = "ben@benjordan.net";
		$from = $email['email'];
		$name = $email['name'];
		$email_message = $email['message'];
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$name.' <'.$from.'>' . "\r\n";
		$headers .= 'Bcc: ben@benjordan.net' . "\r\n";
		$subject = 'Contact from LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>Message from LifeStories.com</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 20px;">
		  <table style="background: #fff; border: 2px solid #d4d5d6; margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 26px;">
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" /><br />
		      <p id="title">Message from: '.$name.' ('.$from.')</p>
		      <p><strong>Their message:</strong></p>
		      <p>'.$email_message.'</p>
		      </td>
		    </tr>
		  </table>
		</div>
		</body>
		</html>
		';
		
		// Send some mail sir
		return email::mymail($to, $subject, $message);
	}
	
	public function send_testimonial($email) {
		$to = "chad@lifestories.com,betabitsistemas@gmail.com";
		$from = $email['email'];
		$name = $email['name'];
		$email_message = $email['message'];

        $headers = "MIME-Version: 1.1\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
        $headers .= "From: no-reply@betabit.com.br\n";
		$headers .= 'Return-path: '.$name.' <'.$from.'>' . "\n";
		//$headers .= 'Bcc: betabitsistemas@gmail.com' . "\r\n";
		$subject = 'Testimonial from LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>Testimonial from LifeStories.com</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 20px;">
		  <table style="background: #fff; border: 2px solid #d4d5d6; margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 26px;">
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" /><br />
		      <p id="title">Testimonial from: '.$name.' ('.$from.')</p>
		      <p><strong>Their message:</strong></p>
		      <p>'.$email_message.'</p>
		      </td>
		    </tr>
		  </table>
		</div>
		</body>
		</html>
		';
		
        ini_set( 'display_errors', 1);
		// Send some mail sir
        return email::mymail($to, $subject, $message);                                  
	}
	
	public function welcome($email, $first_name) {
		$db = new db();
		$to = $email;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: LifeStories.com <info@lifestories.com>' . "\r\n";
		$subject = 'Welcome to LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>Welcome to LifeStories.com</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 20px;">
		  <table style="background: #fff; border: 2px solid #d4d5d6; margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 26px;">
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" /><br />
		      <p id="title">Thank you for signing up to LifeStories.com, '.$first_name.'</p>
		      <p><strong>We are excited for you to begin writing your life story!</strong></p>
		      <p>Remember you can invite others to read your story AND they can <strong>add content</strong> to your story.  So, get them involved; it\'s exciting and fun for all!</p>
		      <p>We are passionate about helping you write your life story!</p>
		      <p>Contact us any time if you need help.  Helping you is what we do!</p>
		      <p><a href="'.website_root_url.'/login/">Click Here to visit LifeStories.com</a></p>
		      <p>Thank you!</p><br />
		   	  <hr />
		   	  <p id="footer">LifeStories.com</p>
		      </td>
		    </tr>
		  </table>
		</div>
		</body>
		</html>
		';
		
		// Send some mail sir
		email::mymail($to, $subject, $message);
	
	}
	
	public function tell_others($email) {
		$to = $email['friends_email'];
		$from = $email['email'];
		$name = $email['name'];
		$email_message = $email['message'];
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$name.' <'.$from.'>' . "\r\n";
		// $headers .= 'Bcc: ben@benjordan.net' . "\r\n";
		$subject = 'Message from LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>Message from LifeStories.com</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 20px;">
		  <table style="background: #fff; border: 2px solid #d4d5d6; margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 26px;">
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" /><br />
		      <p id="title">Message from: '.$name.' ('.$from.')</p>
		      <p><strong>Their message:</strong></p>
		      <p>'.$email_message.'</p>
		      </td>
		    </tr>
		  </table>
		</div>
		</body>
		</html>
		';
		
		// Send some mail sir
		return email::mymail($to, $subject, $message);
	}
	
	public function invite($name, $email, $book_id, $inviteID=0) {
		$question = questions::get_question($question);
		$book = books::by_id($book_id);
		$book = $book[0];
		$cover = books::get_cover($book->cover);
		if ( strlen($book->image_url) > 0 ) {
			$book_cover = website_root_url."/media/covers/" .$book->image_url;
		} else {
			$book_cover = website_root_url."/theme/images/books/" .$cover[0]->image_url;
		} 
        
        $inviteInfo = '';
        if ($inviteID > 0)
            $inviteInfo = 'invite-'.$inviteID;
		
		$db = new db();
		$to = $email;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: LifeStories.com <info@lifestories.com>' . "\r\n";
		// $headers .= 'Bcc: ben@benjordan.net' . "\r\n";
		$subject = 'You Have Been Invited to LifeStories.com';
		$message = '
		<html>
		<head>
		  <title>You Have Been Invited to LifeStories.com</title>
		</head>
		<body style="background: #F9FBF4;">
		<div style="width: 100%; height: 100%; background-color: #F9FBF4; padding: 10px;">
		
		  <table width="500px">
		    <tr>
		      <td>
		      	<img src="'.website_root_url.'/theme/images/life_stories_logo.png" />
		      </td>
		    </tr>
		  </table>
		  
		  <table style="margin: 10px;" width="600px">
		    <tr>
		      <td width="180">
		      	<div style="position: relative; width: 100%; float: left;">
					<a style="width: 178px;
							  height: 210px;
							  font-family: Georgia, serif;
							  font-size: 19px;
							  font-weight: normal;
							  line-height: 21px;
							  font-style: italic;
							  color: #B7A1C9;
							  cursor: pointer;
							  text-shadow:0 -2px 1px rgba(0,0,0,0.9);
							  text-decoration: none;
							  text-align: center;
							  position: absolute;
							  top: 40px;
							  left: 0;
							  z-index: 10;
							  "
						href="'.website_root_url.'/register/'.$inviteInfo.'">'.$book->title.'</a>
                        <img src="'.$book_cover.'" style="width: 178px; height: 273px; z-index: 1;" />
					</div>
		      </td>
		      <td width="350" style="padding: 0 20px;">
		      	<h3>You have been invited to read the life story of your friend!</h3>
		      	<p><strong>So, what does this mean?</strong></p>
		      	<p>Your friend has signed up at LifeStories.com and is working on writing their life story one question at a time.</p>
                <p>They would like for you to help them. By clicking the button below you can login, read your friend&#39;s content, and add content to collaborate!</p>
		      	<p>You will also have access to begin writing your journey, and a library for this purpose will be created.</p>
		      	<p>It&#39;s fun, easy and FREE!</p>
		      	<p>And, most importantly, nothing is more important than this!</p>
		      	<p>Enjoy!</p>
		      	<p>Your faithful friends at LifeStories.com</p>
		      </td>
		    </tr>
		  </table>
		  
		  <table style="margin: 10px;" width="500px">
		    <tr>
		      
		    </tr>
		  </table>
		  
		  <table style="margin: 10px;" width="500px">
		    <tr>
		      <td style="padding: 20px 0;">
		        <a href="'.website_root_url.'/register/'.$inviteInfo.'" style="background: #B7A1C9; padding: 15px 20px; color: #fff; text-transform: uppercase;">Share your answers Now!</a>
		      </td>
		    </tr>
		  </table>
		  
		</div>
		</body>
		</html>
		';
		
		// Send some mail sir
		email::mymail($to, $subject, $message);
	}
	

}
?>