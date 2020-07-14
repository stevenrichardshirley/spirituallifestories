<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    if ( $page->content != 'invite' ) {
    	
    	$pieces = explode("-", $page->content);
    	$friend_id = $pieces[2];
    	$friend = friends::by_id($friend_id)[0];
      $book_id = $pieces[1];
      $book = books::get_book($user->user_id, $book_id);
      $book = $book[0];
    }
    
    $invited = friends::invited($user->user_id, $book_id);
    
    if ( isset($_POST['delete']) ) {
	    $query= "DELETE FROM user_book_permissions WHERE id = '{$friend_id}'";
	    $result= mysql_query($query);
	    $messagefail =  "The person has been univited!";
	    header('Location:/invite/');
    }
    
    if ( isset($_POST['submit']) ) {
        $db = new db();
        $user_id = $_POST['user_id'];
        $user_name = mysql_real_escape_string($_POST['user_name']);
        $name = mysql_real_escape_string($_POST['name']);
        $email = mysql_real_escape_string($_POST['email']);
        $relation = mysql_real_escape_string($_POST['relation']);
        
        $exist = books::invite_exist($user_id, $book_id, $email);
        if ( $exist == "true" ) {

        		if ( isset($friend_id) ) {
        			if (isset($_POST['resend'])) {
            		$send = email::invite($user_name, $email, $book_id);
            		$messagepass = "We have updated your friend's information and resent the invite.";
            	} else {
	            	$messagepass = "We have updated your friend's information.";
	            	// header('Location:/invite/');
            	}
            	            	
            	$query = "UPDATE user_book_permissions SET name = '{$name}', email = '{$email}', relation = '{$relation}' WHERE id = '{$friend_id}'";
					$result = mysql_query($query);
					
            } else {
            	$send = email::invite($user_name, $email, $book_id);
	            $messagefail = "You have already invited this person to contribute to this book. We have resent their invitation.";
            }
           
        } else {
        
            $send = email::invite($user_name, $email, $book_id);
            
            $query = "INSERT INTO user_book_permissions ( user_id, book_id, name, email, relation ) VALUES ( '{$user_id}', '{$book_id}', '{$name}', '{$email}', '{$relation}' )";
            $result = mysql_query($query);
        
            if (mysql_affected_rows() == 1) {
                // success
                $messagepass = "Your friend has been invited to share your story.";
                
            } else {
                // failed
                $messagefail = "There has been an error inviting your friend. An administrator has been notified and it will be looked at as soon as possible.";
                $messagefail .= "<br />". mysql_error();
            }
        }
        
        header('Location:/invite/');
    }
    
    $friend = friends::by_id($friend_id)[0];

    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">
    <div class="width_setter">
    <?php 
        if(!empty($messagepass)) {
            echo "<div class=\"message_pass\">{$messagepass}</div>";
        } elseif (!empty($messagefail)) {
            echo "<div class=\"message_fail\">{$messagefail}</div>";
        }
        ?>
    <div class="column75">
        <?php 
        $cover = books::get_cover($book->cover);
        $slug = $book->slug;
        if ( strlen($book->image_url) > 0 ) { ?>
            <div class="custom_book">
                <div class="img_holder" style="background-image: url(/media/covers/<?php echo $book->image_url; ?>);"></div>
                <div class="title_box">
                <?php if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                <? } else { ?>
                <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                <?php } ?>
                
                </div>
            </div>
            <?php } else { ?>
            <div class="isolated_book" style="background: url(<?php echo $theme->book($cover[0]->image_url); ?>) top left no-repeat;">
                <?php if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                <? } else { ?>
                <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                <?php } ?>
            </div>
            <?php } ?>
        
        <div id="invite_content">
            <h2 class="tk-freight-sans-pro">Invite Someone to Help Tell Your Story</h2>
            <p>
            Invite your friends and family to help contribute to your story.  Share the opportunity to bond and reminisce.
            </p>
            
            <form method="post" action="" id="new_book" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user->user_id; ?>" />
            <input type="hidden" name="user_name" id="user_name" value="<?php echo $user->first_name; ?> <?php echo $user->last_name; ?>" />
            <div class="form50">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" <?php if ( isset($friend_id) ) { echo 'value="' .$friend->name. '"'; } ?> class="field50" />
            </div>
            <div class="form50">
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" <?php if ( isset($friend_id) ) { echo 'value="' .$friend->email. '"'; } ?> class="field50" />
            </div>
            
            <div class="form50">
                <label for="relation">Relation:</label>
                <input type="text" name="relation" id="relation" <?php if ( isset($friend_id) ) { echo 'value="' .$friend->relation. '"'; } ?> class="field50" />
            </div>
            
            <?php if ( isset($friend_id) ) { ?>
            	<div class="form50">
            		<input type="checkbox" name="resend" value="resend"> Resend Invitation to Person?
            	</div>
            <?php } ?>
            
            <div class="form100">
                <input type="submit" name="submit" id="submit" <?php if ( isset($friend_id) ) { echo 'value="Update Person"'; } else { echo 'value="Invite Person"'; } ?>value="Invite Person" class="form_button_small" />
            </div>
            
            </form>
        </div>
    </div>
    <?php if ( isset($friend_id) ) { ?>
	    <div class="column25">
	        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to univite this person? If you agree they will immediately lose access to share.');">
		      	<input type="hidden" name="friend_id" id="friend_id" value="<?php echo $friend_id; ?>" />
		      	<input type="hidden" name="delete" id="delete" value="delete" />
					<input type="submit" name="uninvite" value="Uninvite this person?" class="form_button_small" />
				</form>
	    </div>
    <?php } ?>   
    </div> <!-- width_setter -->    
</div> <!-- main -->

<?php $theme->load('footer'); ?>