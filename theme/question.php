<?php
    session_start();
    $user = user::identify();
    if ( !$user ) {
        $mode_readonly = true;
        //utils::redirect( utils::url('home') );
    }
    else
        $user_id = $user->user_id;

    include "summit/wideimage/WideImage.php";
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    //$theme->load('header');
    
    list($none, $question_id, $delphoto, $answer_id) = explode('-', $page->content);
    
    $next_question_id = $question_id + 1;
    $question = questions::get_question($question_id);
    $next_question = questions::get_question($next_question_id);
    $cat_id = $question[0]->category;
    
    $answer_id = intval($answer_id);
    
    $user_first_name = $user->first_name;
    
    if ($mode_readonly && $answer_id > 0)
        list($user_id, $user_first_name) = mysql_fetch_row(mysql_query("SELECT qa.user_id, u.first_name FROM question_answers qa INNER JOIN users u on qa.user_id=u.user_id WHERE qa.id=$answer_id"));
        
        
    if ($user_id <= 0)
    {
        // leaving this page, getting back to HOME PAGE
        utils::redirect( utils::url('home') );
        exit;
    }
    
    $category = questions::get_category_by_id($cat_id);
    $answer = questions::get_answer($user_id, $question[0]->id);
    
    if ($mode_readonly)
    {
        // facebook header
        $GLOBALS['fb_title']       = $question[0]->content;
        $GLOBALS['fb_description'] = strip_tags($answer[0]->content);
    }
    
    if ($delphoto == 'xphoto')
    {
        list($photourl) = mysql_fetch_row(mysql_query("SELECT image_url FROM question_photos WHERE question_id={$question_id}"));
        @unlink(dirname($_SERVER['SCRIPT_FILENAME'])."/media/photos/".$photourl);
        mysql_query("DELETE FROM question_photos WHERE question_id={$question_id}");
        header('location: question-'.$question_id);
        exit;
    }
    
    $photos = questions::get_photos($user_id, $question[0]->id); $photo_count = count($photos);


    if ($_POST['autosave'] == 1)
    {
        $timestamp = date("Y-m-d, G:i:s");
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
        mysql_query($query);
        
        list($answer_id) = mysql_fetch_row(mysql_query("SELECT id FROM question_answers WHERE user_id={$user->user_id} AND question_id={$question[0]->id}"));
        
        print $answer_id;
        exit;
    }
    
    if ( isset($_FILES['uploaded']) ) {
    
        $timestamp = date("Y-m-d, G:i:s");
        
        if ( is_uploaded_file($_FILES['uploaded']['tmp_name']) ) {
        
            $limit = 3500000;
            $file_size = $HTTP_POST_FILES['uploaded']['size'];
            
            /*if ( $file_size >= $limit )
            { 
                $messagefail = "Your file is too large. Please scale down the photo and try uploading again.<br />"; 
                 
            } else*/ {
                // Take the file name and clean it up a bit
                $file_name = $_FILES['uploaded']['name'];
                    
                // Where are we uploading?
                //$target = "{$_SERVER["DOCUMENT_ROOT"]}/media/photos/";
                $target = dirname($_SERVER['SCRIPT_FILENAME'])."/media/photos/";
                
                // Make the full path
                $target = $target . $file_name; 
                
                if( move_uploaded_file($_FILES['uploaded']['tmp_name'], $target) ) {
                    // $messagepass = "The file has been uploaded.<br />";
                    
                } else {
                    $messagefail = "Sorry, there was a problem uploading your file.<br />";
                }
                
                $image = WideImage::load($target);
                $resized = $image->resize(700, null, 'outside', 'down'); // 500
                $save = $resized->saveToFile($target);
                
                $image = WideImage::load($target);
                $resized = $image->resize(100, 100, 'outside', 'down');
                $cropped = $resized->crop(0,0,100,100);
                $save = $cropped->saveToFile(dirname($_SERVER['SCRIPT_FILENAME']).'/media/photos/thumbs/'.$file_name);
            }
            
            $image_url = $file_name;
            
            $clear = mysql_query("DELETE FROM question_photos WHERE user_id = {$user->user_id} AND question_id={$question[0]->id}");
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
    
    $answer = questions::get_answer($user_id, $question[0]->id);
    $photos = questions::get_photos($user_id, $question[0]->id); $photo_count = count($photos);

?>

<script type="text/javascript">

function contributionApproval(iid, iquestion, istatus, ifriend) {
        if (istatus == '2')
            var conBox = confirm("Are you sure you want to deny this contribution from your friend?");
        else
            var conBox = confirm("Are you sure you want to approve contribution from your friend? Approving this contribution will add their answer to yours.");
        if(conBox) {
            $.post("<?=ROOT_DIRECTORY?>/contrib_approve", { id: iid, question: iquestion, status: istatus, friend: ifriend })
                .done(
               function() {
                document.location.reload();
               });

        } else {
            return;
        }
    }
function onremovephoto()
{
    if (confirm('Are you sure?'))
    {
        document.location.href='<?=ROOT_DIRECTORY?>/questions/question-<?=$question_id?>-xphoto';
    }
}

</script>

<?php
    $theme->load('header');
    $theme->load('header_content');

?>

<style>
@import "<?=utils::url('theme')?>/css/uploading.css";
</style>

<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><?if(!$mode_readonly){?><a href="<?php echo utils::url('home'); ?>library/my-story/"><?}?><?php echo $user_first_name; ?>'s spiritual life story</a></li>
            <li><?if(!$mode_readonly){?><a href="<?php echo utils::url('home'); ?>category/<?php echo $category[0]->slug; ?>/"><?}?><?php echo $category[0]->title; ?></a></li>
            <li>Question</li>
        </ul>
    </div>
</div>

<div class="width_setter">
    <?if(!$mode_readonly){?>
        <div class="next_question"><a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $next_question_id; ?>/">View Next Question &raquo;</a></div>
    <?}?>
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
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            
            <div id="question"><?php echo $question[0]->content; ?></div>
            
            
            <p class="descriptionText">
            <?if($mode_readonly){?>
                
            <?}else{?>
                Write the answer of this question at the content box below
            <?}?>
            </p>

            
            <?php 
            if(!empty($messagepass)) {
                echo "<div class=\"message_pass\">{$messagepass}</div>";
            } elseif (!empty($messagefail)) {
                echo "<div class=\"message_fail\">{$messagefail}</div>";
            }
            ?>
            
            <div class="form_row">
                <?if($mode_readonly){?>
                    <p><b><?=$answer[0]->content?></b></p>
                <?}else{?>
                    <textarea name="content" id="content" tabindex="1" onkeyup="onwaitAutoSave()"><?=$answer[0]->content?></textarea>
                    <div style="text-align:right" id="divStatus"></div>
                <?}?>
            </div>
            
            <script>
            $(document).ready(function(){
                $('#content').redactor({
                    keyupCallback: function(e){
                        onwaitAutoSave();
                    },
                    buttons: ['bold', 'italic', 'deleted', '|', 'unorderedlist', 'orderedlist'],
                });
                $('#link_image').fancybox({type:'image'});
            });

            var tm=0,clearStatus=0;
            
            function onwaitAutoSave()
            {
                $('#divStatus').html('changed');
                clearTimeout(tm);
                tm = setTimeout('doautoSave()', 1500);
            }
            function doautoSave()
            {
                clearTimeout(clearStatus);
                $('#divStatus').html('saving...');
                $.post('<?=$_SERVER['REQUEST_URI']?>', {autosave:1, content: $('#content').val()})
                .done(function(s){
                    if (!$('#shareDiv').is(':visible'))
                    {
                        $('#shareDiv').show();
                        $('#linkFacebook').attr('href', $('#linkFacebook').attr('href')+s);
                        $('#linkTwitter').attr('href', $('#linkTwitter').attr('href')+s);
                    }
                    
                    $('#divStatus').html('content saved!');
                    clearStatus = setTimeout(function(){
                        $('#divStatus').html('');
                    },5000);
                    
                });
            }
        
            </script>
            
            <?/*<div class="form_row">
                <input type="submit" name="submit" value="Save Content" class="form_button" />
            </div> */?>
            
            <?if(!$mode_readonly){?>
                <div id="bottom_nav_holder">
                    <div class="width_setter">
                    <input type="submit" name="submit" value="Save Content" class="form_button" />
                    <div class="invite_others" style="float:right;"><a href="<?php echo utils::url('home'); ?>invite/">Invite Others</a></div>
                    </div>
                </div>
            <?} else {?>
            
                <div class="form_row" style="overflow:hidden;width:auto;text-align:left;padding:20px;background-color:rgba(255,255,50,0.5)">
                    <div style="float:left;width:60%">
                        <b>You can join SpiritualLifeStories by clicking on this link!  Join, it's FREE and you can start writing your story AND sharing it!</b>
                    </div>
                    <div style="float:right;margin-top:5px">
                        <input type="button" value="Sign Up Now! It's free!" class="form_button" onclick="document.location.href='../register'">                
                    </div>
                </div>
            
            <?}?>
            
        </form>
        
        </div>
        
        <?if(!$mode_readonly){?>
            <div class="sidebar" id="shareDiv" <?if($answer[0]->id <= 0){?>style="display:none"<?}?>>
                <p>Share this question on your social network</p>
              <a href="https://www.facebook.com/sharer/sharer.php?u=http://<?=$_SERVER['HTTP_HOST'].ROOT_DIRECTORY?>/questions/question-<?=$question_id?>-0-<?=$answer[0]->id?>" target="_blank" id="linkFacebook"><img src="<?php echo utils::url('theme'); ?>images/facebook.png" border=0 /></a>
              <a href="https://twitter.com/intent/tweet?text=Check my Spiritual Life Stories&url=http://<?=$_SERVER['HTTP_HOST'].ROOT_DIRECTORY?>/questions/question-<?=$question_id?>-0-<?=$answer[0]->id?>" id="linkTwitter" target="_blank"><img src="<?php echo utils::url('theme'); ?>images/twitter.png" border=0 /></a>
            </div>
        <?}?>
        
        <div class="sidebar">
            
            <?php if ( $photo_count > 0 ) { ?>
            <?if(!$mode_readonly){?>
                <h4>Photo for this Question</h4>
            <?}?>
            
            <?php foreach ($photos as $photo) { ?>
            
                <div style="float: left;overflow: hidden;margin-bottom: 10px;">
                    <div style="float:left"><a href="<?php echo utils::url('home'); ?>media/photos/<?=$photo->image_url?>" id="link_image"><img src="<?php echo utils::url('home'); ?>media/photos/thumbs/<?php echo $photo->image_url; ?>" border=0 title="Click here to see it larger"></a></div>
                    <div style="float:left;margin-left:10px;width:100px"><em style="font-size:12px;">Click on the picture to see it larger.</em></div>
                </div>
            <?php } ?>
            
            <?if(!$mode_readonly){?>
                <p>Uploading a new photo will replace your current photo.</p>
                <p><a href="javascript: onremovephoto()">Remove this photo</a></p>
            <?}?>
            
            <?} 
            
            list($countphotos) = mysql_fetch_row(mysql_query("SELECT count(id) FROM question_photos where user_id={$user_id}"));
            
            if (!$mode_readonly && ($countphotos < total_photos_limit || $photo_count > 0) )
            {
            ?>

                <h4><?=($photo_count > 0?'Change the Photo of this Question':'Add Photo for this Question')?></h4>
        
                <form name="photoForm" action="" method="post" id="question_photo_form" enctype="multipart/form-data" style="text-align: right;">
                    
                    <div style="overflow:hidden;background-image:url('<?=utils::url('theme')?>images/adphotobtn.png');height:40px;width:200px;background-size:100% auto;background-repeat:no-repeat" >
                        <input name="uploaded" type="file" value="<?php $target1 ?>" tabindex="6" style="opacity:0;font-size:100px;height:100px;width:300px" onchange="onselPhoto(this)" accept="image/jpeg">
                        <script>
                        function onselPhoto(f)
                        {
                            if (f.value.length > 0)
                            {
                                //$('#divLoading').show();
                                //document.getElementById('divLoading').style.visibility = 'visible';
                                document.getElementById('divLoading').style.display='block';
                                document.photoForm.submit();
                            }
                        }
                        </script>
                    
                    </div>
                    <div id="divLoading" style="text-align:left;margin-top:20px;display:none">
                        <div style="width:200px;">
                            <div id="ballsWaveG">
                                <div id="ballsWaveG_1" class="ballsWaveG"></div>
                                <div id="ballsWaveG_2" class="ballsWaveG"></div>
                                <div id="ballsWaveG_3" class="ballsWaveG"></div>
                                <div id="ballsWaveG_4" class="ballsWaveG"></div>
                                <div id="ballsWaveG_5" class="ballsWaveG"></div>
                                <div id="ballsWaveG_6" class="ballsWaveG"></div>
                                <div id="ballsWaveG_7" class="ballsWaveG"></div>
                                <div id="ballsWaveG_8" class="ballsWaveG"></div>
                            </div>                        
                        </div>
                        <center style="font-weight:bold"><i>Uploading, please wait...</i></center>
                    </div>
                    <?/*<input type="submit" name="submit_photo" value="Click here to add photo" class="form_button_small" style="width: 100%;" />*/?>
                    
                    
                    
                    
                    
                </form>
            <?}?>
            
            <?if(!$mode_readonly){?>
                <div style="text-align:left;margin-top:30px;">
                    <p><em>Only one photo per question</em></p>
                    <span style="<?if($countphotos >= total_photos_limit){?>color:red<?}?>">Your photos: <?=$countphotos?> of <?=total_photos_limit?>&nbsp;&nbsp;<img src="<?=ROOT_DIRECTORY?>/theme/images/info.gif" title="You can only keep 150 photos at your account"></span>
                </div>
            <?}?>

            <p>&nbsp;</p>
            
            <!-- <h4>Invited Users for this Question</h4> 
            <p><em>No one has been invited to this question</em></p>
            <p><a href="<//?php echo utils::url('home'); ?>invite/<//?php echo $page->content; ?>/">Click here</a> to invite someone to contribute</p>-->

        </div>
        
        <div class="column75">        
                                    
            <!-- New contributions from friends -->
        
            <?php
                $friends_answers = questions::get_friends_answers_unapproved($user_id, $question[0]->id);
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
                $friends_answers = questions::get_friends_answers_approved($user_id, $question[0]->id);
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
                $friends_answers = questions::get_friends_answers_denied($user_id, $question[0]->id);
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