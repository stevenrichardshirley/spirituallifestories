<?php
    session_start();
    $user = user::identify();
    if ( !$user ) {
        utils::redirect( utils::url('home') );
    } 
    
    include "summit/wideimage/WideImage.php";
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    
    $question_id = str_replace('question-', '', $page->content);
    $next_question_id = $question_id + 1;
    $question = questions::get_question($question_id);
    $next_question = questions::get_question($next_question_id);
    $cat_id = $question[0]->category;
    
    $category = questions::get_category_by_id($cat_id);
    $answer = questions::get_answer($user->user_id, $question[0]->id);
    $photos = questions::get_photos($user->user_id, $question[0]->id); $photo_count = count($photos);
    
    if ( isset($_POST['submit_photo']) ) {
    
        
        $timestamp = date("Y-m-d, G:i:s");
        
        if ( is_uploaded_file($_FILES['uploaded']['tmp_name']) ) {
        
            $limit = 3500000;
            $file_size = $HTTP_POST_FILES['uploaded']['size'];
            
            if ( $file_size >= $limit )
            { 
                $messagefail = "Your file is too large. Please scale down the photo and try uploading again.<br />"; 
                 
            } else {
                // Take the file name and clean it up a bit
                $file_name = $_FILES['uploaded']['name'];
                    
                // Where are we uploading?
                $target = "{$_SERVER["DOCUMENT_ROOT"]}/media/photos/";
                
                // Make the full path
                $target = $target . $file_name; 
                
                if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
                    // $messagepass = "The file has been uploaded.<br />";
                    
                } else {
                    $messagefail = "Sorry, there was a problem uploading your file.<br />";
                }
                
                $image = WideImage::load($_SERVER["DOCUMENT_ROOT"].'/media/photos/'.$file_name);
                $resized = $image->resize(700, 500, 'outside', 'down');
                $save = $resized->saveToFile($_SERVER["DOCUMENT_ROOT"].'/media/photos/'.$file_name);
                
                $image = WideImage::load($_SERVER["DOCUMENT_ROOT"].'/media/photos/'.$file_name);
                $resized = $image->resize(100, 100, 'outside', 'down');
                $cropped = $resized->crop(0,0,100,100);
                $save = $cropped->saveToFile($_SERVER["DOCUMENT_ROOT"] .'/media/photos/thumbs/'.$file_name);
            }
            
            $image_url = $file_name;
            
            $clear = mysql_query("DELETE FROM question_photos WHERE user_id = " .$user->user_id. "");
            $query = "INSERT INTO question_photos ( user_id, question_id, main, title, image_url, timestamp ) VALUES ( " .$user->user_id. ", " .$question[0]->id. ", '', '', '{$image_url}', '{$timestamp}' )";
            $result = mysql_query($query);
        }
    }
        
    if ( isset($_POST['submit']) ) {

        $content = mysql_escape_string($_POST['content']);        
        
        $check = questions::check_for_answer($user->user_id, $question[0]->id);
        
        if ( $check == 1 ) {
        
            $query = "UPDATE question_answers SET
                    content = '{$content}',
                    timestamp = '{$timestamp}'
                WHERE user_id = " .$user->user_id. " AND question_id = " .$question[0]->id. "";
            
        } else {
        
            $query = "INSERT INTO question_answers ( user_id, question_id, content, timestamp ) VALUES ( " .$user->user_id. ", " .$question[0]->id. ", '{$content}', '{$timestamp}' )";

        }
        
        $result = mysql_query($query);
        
        if (mysql_affected_rows() == 1) {
            // success
                $messagepass .= "Your answer to this question has been updated!";
        } else {
            // failed
            $messagefail = "There has been an error adding your answer. An administrator has been notified and it will be looked at as soon as possible.";
            $messagefail .= "<br />". mysql_error();
        }
    }
    
    $answer = questions::get_answer($user->user_id, $question[0]->id);
    $photos = questions::get_photos($user->user_id, $question[0]->id); $photo_count = count($photos);

?>

<script type="text/javascript">

function contributionApproval(id, question, status, friend) {
        var conBox = confirm("Are you sure you want to approve contribution from your friend? Approving this contribution will add their answer to yours.");
        if(conBox) {
            $.post("/contrib_approve", { id: id, question: question, status: status, friend: friend },
               function() {
                 window.location.href=window.location.href;
               });

            location.reload();
        } else {
            return;
        }
    }
</script>

<?php
    $theme->load('header');
    $theme->load('header_content');

?>
<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><a href="<?php echo utils::url('home'); ?>library/my-story/"><?php echo $user->first_name; ?>'s spiritual life story</a></li>
            <li><a href="<?php echo utils::url('home'); ?>category/<?php echo $category[0]->slug; ?>/"><?php echo $category[0]->title; ?></a></li>
            <li>Question</li>
        </ul>
    </div>
</div>

<div class="width_setter">
    <div class="next_question"><a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $next_question_id; ?>/">View Next Question &raquo;</a></div>
</div>

<div id="main" role="main">
    <div class="width_setter">
        
<!--
    <div id="top_internal_banner">
        <a href="<//?php echo utils::url('home'); ?>invite/"><img src="<//?php echo utils::url('theme'); ?>images/banner-invite_to_collab.jpg" /></a>
    </div>
-->
        
        <div class="writing_page_column shadow">
        
        <form action="" method="post" id="question_info_form" enctype="multipart/form-data" >
        <input type="hidden" name="question_id" value="<?php echo $question[0]->id; ?>" />
        <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
            
            <div id="question"><?php echo $question[0]->content; ?></div>
            
            <?php 
            if(!empty($messagepass)) {
                echo "<div class=\"message_pass\">{$messagepass}</div>";
            } elseif (!empty($messagefail)) {
                echo "<div class=\"message_fail\">{$messagefail}</div>";
            }
            ?>
            
            <div class="form_row">
                <textarea name="content" id="content" tabindex="1"><?php echo $answer[0]->content; ?></textarea>
            </div>
            <div class="form_row">
                <input type="submit" name="submit" value="Save Content" class="form_button" />
            </div>
            
            <div id="bottom_nav_holder">
                <div class="width_setter">
                <input type="submit" name="submit" value="Save Content" class="form_button" />
                <div class="invite_others" style="float:right;"><a href="<?php echo utils::url('home'); ?>invite/">Invite Others</a></div>
                </div>
            </div>
            
        </form>
        
        </div>
        
        <div class="sidebar">
          <a href=""><img src="<?php echo utils::url('theme'); ?>images/facebook.png" onClick="alert('Coming Soon. We appreciate your patience!')" /></a>
          <a href=""><img src="<?php echo utils::url('theme'); ?>images/twitter.png" onClick="alert('Coming Soon. We appreciate your patience!')" /></a>
        </div>
        
        <div class="sidebar">
            
            <?php if ( $photo_count > 0 ) { ?>
            <h4>Photo for this Question</h4>
            <?php foreach ($photos as $photo) { ?>
                <img src="<?php echo utils::url('home'); ?>media/photos/thumbs/<?php echo $photo->image_url; ?>" />
            <?php } ?>
            <p>Uploading a new photo will replace your current photo.</p>
            <?php } ?>
            <h4>Add Photo for this Question</h4>
    
            <form action="" method="post" id="question_photo_form" enctype="multipart/form-data" style="text-align: right;">
                <input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="6" />
                <input type="submit" name="submit_photo" value="Add Photo" class="form_button_small" style="width: 100%;" />
            </form>
            <p>&nbsp;</p>
            
            <!-- <h4>Invited Users for this Question</h4> 
            <p><em>No one has been invited to this question</em></p>
            <p><a href="<//?php echo utils::url('home'); ?>invite/<//?php echo $page->content; ?>/">Click here</a> to invite someone to contribute</p>-->

        </div>
        
        <div class="column75">        
                                    
            <!-- New contributions from friends -->
        
            <?php
                $friends_answers = questions::get_friends_answers_unapproved($user->user_id, $question[0]->id);
                $answer_count = count($friends_answers);
                if ( $answer_count > 0 ) {
                ?>
                <a name="contributions"></a>
                <h3><img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" />Friends Answers for this Question that need Approval:</h3>
        
                <ul id="friends_answers">
                <?php
                
                foreach ($friends_answers as $friend_answer) {
                    $friend = user::get_by_id($friend_answer->user_id);
                    $friend = questions::get_friend($friend->email);
                ?>
                    <li>
                    <strong>Contribution provided by: <?php echo $friend[0]->name; ?>, <?php echo $friend[0]->relation; ?> on <?php echo date('F d, Y', strtotime($friend_answer->timestamp)); ?></strong>
                    <div class="approval_buttons">
                    <div class="deny">
                    <a href="javascript: contributionApproval('<?php echo $friend_answer->id; ?>', '<?php echo $question_id; ?>', '2', '<?php echo $friend[0]->id; ?>');">Deny</a>
                    </div>
                    <div class="approve">
                    <a href="javascript: contributionApproval('<?php echo $friend_answer->id; ?>', '<?php echo $question_id; ?>', '1', '<?php echo $friend[0]->id; ?>');">Approve</a>
                    </div>
                    
                    </div>
                    <div class="answer"><?php echo $friend_answer->content; ?></div>
                    
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>
            
            
            <!-- Approved contributions from friends -->
            
            <?php
                $friends_answers = questions::get_friends_answers_approved($user->user_id, $question[0]->id);
                $answer_count = count($friends_answers);
                if ( $answer_count > 0 ) {
                ?>
                <h3>Approved Friends Answers for this Question:</h3>
        
                <ul id="friends_answers">
                <?php
                
                foreach ($friends_answers as $friend_answer) {
                    $friend = user::get_by_id($friend_answer->user_id);
                    $friend = questions::get_friend($friend->email);
                ?>
                    <li>
                        <strong>Contribution provided by: <?php echo $friend[0]->name; ?>, <?php echo $friend[0]->relation; ?> on <?php echo date('F d, Y', strtotime($friend_answer->timestamp)); ?></strong>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>
            
            <!-- Denied contributions from friends -->
            
            <?php
                $friends_answers = questions::get_friends_answers_denied($user->user_id, $question[0]->id);
                $answer_count = count($friends_answers);
                if ( $answer_count > 0 ) {
                ?>
                <h3>Denied Friends Answers for this Question:</h3>
        
                <ul id="friends_answers">
                <?php
                
                foreach ($friends_answers as $friend_answer) {
                    $friend = user::get_by_id($friend_answer->user_id);
                    $friend = questions::get_friend($friend->email);
                ?>
                    <li>
                        <strong>Contribution provided by: <?php echo $friend[0]->name; ?>, <?php echo $friend[0]->relation; ?> on <?php echo date('F d, Y', strtotime($friend_answer->timestamp)); ?></strong>
                    </li>
                <?php } ?>
                </ul>
            <?php } ?>


        </div>
        
    </div>

</div>

<?php $theme->load('footer'); ?>