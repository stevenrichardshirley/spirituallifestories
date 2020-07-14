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
    $theme->load('header_content');
    
    
    $friend_id = substr($page->content, -1);
    $friend = user::get_by_id($friend_id);
    
    $book_id = books::get_my_story($friend_id);

    $question_slug = substr_replace($page->content ,"",-2);
    
    $question_id = str_replace('question-', '', $question_slug);
    $next_question_id = $question_id + 1;
    $question = questions::get_question($question_id);
    $next_question = questions::get_question($next_question_id);
    $cat_id = $question[0]->category;
    
    $category = questions::get_category_by_id($cat_id);
    $answer = questions::get_answer($friend_id, $question[0]->id);
    $next_answer = questions::get_answer($friend_id, $next_question_id);
        
    if ( isset($_POST['submit']) ) {

        $content = mysql_escape_string($_POST['content']);        
        $timestamp = date("Y-m-d, g:i:s");

        
        $query = "INSERT INTO question_contributions ( user_id, friend_id, question_id, content, timestamp ) VALUES ( " .$user->user_id. ", " .$friend_id. ", " .$question[0]->id. ", '{$content}', '{$timestamp}' )";

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
    
    $answer = questions::get_answer($friend_id, $question[0]->id);

?>

<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><a href="<?php echo utils::url('home'); ?>friends_book/book-<?php echo $book_id[0]->id; ?>"><?php echo $friend->first_name; ?>'s spiritual life story</a></li>
            <li><a href="<?php echo utils::url('home'); ?>friends_category/<?php echo $category[0]->slug; ?>-<?php echo $friend_id; ?>/"><?php echo $category[0]->title; ?></a></li>
            <li>Question</li>
        </ul>
    </div>
</div>
<?php if ( $next_answer[0]->content != '' ) { ?>
<div class="width_setter">
    <div class="next_question">
    	<a href="<?php echo utils::url('home'); ?>friends_question/question-<?php echo $next_question_id; ?>-<?php echo $friend_id; ?>/">View Next Question &raquo;</a>
    </div>
</div>
<?php } ?>

<div id="main" role="main">
    <div class="width_setter">
            
        <form action="" method="post" id="question_info_form" enctype="multipart/form-data" >
        <input type="hidden" name="question_id" value="<?php echo $question[0]->id; ?>" />
        <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
            <div class="column100">
                <div id="question"><?php echo $question[0]->content; ?></div>
                <div id="user_answer">
                    <?php echo $answer[0]->content; ?>
                </div>
                <h3>Submit your answer for this question that has been answered by <?php echo $friend->first_name; ?> <?php echo $friend->last_name; ?></h3>
            </div>
            
            <div class="column75">
                <?php 
                if(!empty($messagepass)) {
                    echo "<div class=\"message_pass\">{$messagepass}</div>";
                } elseif (!empty($messagefail)) {
                    echo "<div class=\"message_fail\">{$messagefail}</div>";
                }
                ?>
                <div class="form_row">
                    <textarea name="content" id="contrib" tabindex="1"></textarea>
                </div>
                
                <div class="form_row">
                    <input type="submit" name="submit" value="Save Question" class="form_button" />
                </div>
                
            </div>


            </form>
        
    </div>

</div>

<?php $theme->load('footer'); ?>