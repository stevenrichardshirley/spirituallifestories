<?php
    session_start();
    $user = user::identify(true);
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    if ( $page->content != 'invite' ) 
    {
      $pieces = explode("-", $page->content);
      $perm_id = intval($pieces[2]);
      $book_id = intval($pieces[1]);
      
      if (isset($_POST['uninvite']) || isset($_POST['uninvite_form']))
      {
        mysql_query("DELETE FROM user_book_permissions WHERE id = '{$perm_id}'") or die(mysql_error());
        if (isset($_POST['uninvite']))
        {
            print 'ok';
            exit;
        }
      }
      else if (isset($_POST['resend']) || isset($_POST['resend_form']))
      {
          list($user_name, $email) = mysql_fetch_row(mysql_query("SELECT name, email FROM user_book_permissions WHERE id='{$perm_id}'"));
          email::invite($user_name, $email, $book_id, $perm_id);
          if (isset($_POST['resend']))
          {
              print 'ok';
              exit;
          }
      }
      
      $friend = friends::by_id($perm_id);
      $friend = $friend[0];
      $book = books::get_book($user->user_id, $book_id);
      $book = $book[0];
    }
    
    $invited = friends::invited($user->user_id, $book_id);
    
    if ( isset($_POST['delete']) ) {
	    $query= "DELETE FROM user_book_permissions WHERE id = '{$perm_id}'";
	    $result= mysql_query($query);
	    $messagefail =  "The person has been univited!";
	    header('Location:'.ROOT_DIRECTORY.'/invite/');
    }
    
    if ( isset($_POST['submit']) ) 
    {
        $db = new db();
        $user_id = $_POST['user_id'];
        $user_name = mysql_real_escape_string($_POST['user_name']);
        $name = mysql_real_escape_string($_POST['name']);
        $email = mysql_real_escape_string($_POST['email']);
        $relation = mysql_real_escape_string($_POST['relation']);
        
        $exist = books::invite_exist($user_id, $book_id, $email);
        if ( $exist == "true" ) 
        {
            if ( $perm_id>0 )
            {
        	    if (isset($_POST['resend_form'])) 
            	    $messagepass = "We have updated your friend's information and resent the invite.";
            	else 
                    $messagepass = "We have updated your friend's information.";
            	            	
            	$query = "UPDATE user_book_permissions SET name = '{$name}', email = '{$email}', relation = '{$relation}' WHERE id = '{$perm_id}'";
				
                $result = mysql_query($query);
					
            } 
            else 
            {
            	$send = email::invite($user_name, $email, $book_id, $perm_id);
	            $messagefail = "You have already invited this person to contribute to this book. We have resent their invitation.";
            }
        } 
        else 
        {
            $query = "INSERT INTO user_book_permissions ( user_id, book_id, name, email, relation ) VALUES ( '{$user_id}', '{$book_id}', '{$name}', '{$email}', '{$relation}' )";
            $result = mysql_query($query);
            if (mysql_affected_rows() == 1) 
            {
                $send = email::invite($user_name, $email, $book_id, mysql_insert_id());

                $messagepass = "Your friend has been invited to share your story.";
            }
            else 
            {
                // failed
                $messagefail = "There has been an error inviting your friend. An administrator has been notified and it will be looked at as soon as possible.";
                $messagefail .= "<br />". mysql_error();
            }
        }
        
        header('Location:'.ROOT_DIRECTORY.'/invite/');
        exit;
    }
    
    $friend = friends::by_id($perm_id);
    $friend = $friend[0];

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
            <p class="descriptionText">
            Invite your friends and family to read your book AND they can add content to your story, which only you can approve.  
            
            <br /><br />
            When your friends sign up, they will see your book in their library.
            </p>
            
            <form method="post" action="" id="new_book" enctype="multipart/form-data">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user->user_id; ?>" />
            <input type="hidden" name="user_name" id="user_name" value="<?php echo $user->first_name; ?> <?php echo $user->last_name; ?>" />
            <div class="form50">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" <?php if ( $perm_id>0 ) { echo 'value="' .$friend->name. '"'; } ?> class="field50" required />
            </div>
            <div class="form50">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" <?php if ( $perm_id>0 ) { echo 'value="' .$friend->email. '"'; } ?> class="field50" required />
            </div>
            
            <div class="form50">
                <label for="relation">Relation:</label>
                <input type="text" name="relation" id="relation" <?php if ( $perm_id>0 ) { echo 'value="' .$friend->relation. '"'; } ?> class="field50" />
            </div>
            
            <?php if ( $perm_id>0 ) { ?>
            	<div class="form50">
            		<input type="checkbox" name="resend_form" value="resend" id="resend"> <label for="resend" class="normal_label">Resend Invitation to Person?</label>
            	</div>
            <?php } ?>
            
            <div class="form100">
                <input type="submit" name="submit" id="submit" <?php if ( $perm_id>0 ) { echo 'value="Update Person"'; } else { echo 'value="Invite Person"'; } ?>value="Invite Person" class="form_button_small" />
            </div>
            
            </form>
        </div>
    </div>
    <?php if ( $perm_id>0 ) { ?>
	    <div class="column25">
	        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to uninvite this person? If you agree they will immediately lose access to share.');">
		      	<input type="hidden" name="perm_id" id="perm_id" value="<?=$perm_id?>" />
		      	<input type="hidden" name="delete" id="delete" value="delete" />
					<input type="submit" name="uninvite_form" value="Uninvite this person?" class="form_button_small" />
				</form>
	    </div>
    <?php } ?>   
    </div> <!-- width_setter -->    
</div> <!-- main -->

<?php $theme->load('footer'); ?>