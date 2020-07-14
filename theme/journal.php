<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
    $book_id = substr($page->slug, 5);
    
    $book = books::get_book($user->user_id, $book_id);
    $book = $book[0];
    
    if ( isset($_POST['submit']) ) {

        $title = $_POST['title'];
        $slug = utils::slug($_POST['title']);
        $date = date('Y-m-d');
        $content = mysql_escape_string($_POST['content']);        
        
/*
        $check = journal::check_for_answer($user->user_id, $question[0]->id);
        
        if ( $check == 1 ) {
        
            $query = "UPDATE user_book_entries SET
                    title = '{$title}',
                    date = '{$date}',
                    content = '{$content}',
                    timestamp = '{$timestamp}'
                WHERE user_id = " .$user->user_id. " AND book_id = " .$book->id. "";
            
        } else {
*/
        
            $query = "INSERT INTO user_book_entries ( user_id, book_id, title, slug, date, content, timestamp ) VALUES ( " .$user->user_id. ", " .$book->id. ", '{$title}', '{$slug}', '{$date}', '{$content}', '{$timestamp}' )";
        
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
?>

<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><a href="<?php echo utils::url('home'); ?>entries/book-<?php echo $book->id; ?>"><?php echo $user->first_name; ?> <?php echo $user->last_name; ?> - <?php echo $book->title; ?></a></li>
            <li>Add a new entry</li>
        </ul>
    </div>
</div>

<div id="main" role="main">
    <div class="width_setter">
    
        <div id="top_internal_banner">
            <a href="<?php echo utils::url('home'); ?>invite/book-<?php echo $book->id; ?>"><img src="<?php echo utils::url('theme'); ?>images/banner-invite_to_collab.jpg" /></a>
        </div>
    
        <div class="writing_page_column shadow">
        
        <form action="" method="post" id="question_info_form" enctype="multipart/form-data" >
        <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>" />
            
            <h2 id="journal_entry">Journal Entry</h2>
            <div class="form_row">
                <input type="text" name="title" id="title" value="Journal Entry for <?php echo date('l, F jS, Y'); ?>" class="field100" />
            </div>
            
            <?php 
            if(!empty($messagepass)) {
                echo "<div class=\"message_pass\">{$messagepass}</div>";
            } elseif (!empty($messagefail)) {
                echo "<div class=\"message_fail\">{$messagefail}</div>";
            }
            ?>
            
            <div class="form_row">
                <textarea name="content" id="content" rows="10" tabindex="1"><?php echo $answer[0]->content; ?></textarea>
            </div>
            
            <div class="form_row">
                <input type="submit" name="submit" value="Save Entry" class="form_button" />
            </div>
            
        </form>
        
        </div>
        
        <div class="sidebar">
            
            <h4>Previous Entries</h4>
            <ul>
            <?php
            $entries = journal::get_entries($user->user_id, $book->id, 4);
            foreach ( $entries as $entry ) {
            ?>
                <li>
                    <a href="">
                    <?php echo $entry->title; ?>
                    </a>
                </li>
            <?php } ?>
            </ul>
            <div class="form_row">
            <a href="">View All Entries &raquo;</a>
            </div>
        </div>
    

    
    </div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>