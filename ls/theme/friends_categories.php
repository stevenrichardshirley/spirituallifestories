<?php
    session_start();
    $user = user::identify();
    
    if ( !$user ) {
        utils::redirect( utils::url('home') );
    } 
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    $book_id = str_replace('book-', '', $page->content);
    
    $book_owner = books::by_id($book_id);
    $friend_id = $book_owner[0]->user_id;
    $friend = user::get_by_id($friend_id);
    
    if ( isset($_POST['delete']) ) {
	    $query= "DELETE FROM user_book_permissions WHERE book_id = '{$book_id}' AND user_id = '{$friend_id}' AND email='{$user->email}'";
	    $result= mysql_query($query);
	    utils::redirect( utils::url('library') );
    }
        
    $categories = questions::get_categories();
    $answered_questions = questions::total_answered($friend_id);
    $total = questions::count_questions();
    $percent_complete = ($answered_questions/$total)*100;
    $percent_complete = number_format($percent_complete, 0);
    
    $theme->load('header');
    $theme->load('header_content');
?>

<div id="main" role="main">

<div class="width_setter">

    <div class="column50">
        <h2>Chapters of <?php echo $friend->first_name; ?>'s Story</h2>
        <p class="descriptionText">Please click on a chapter of your friend's book to see the responses to their questions. There you can add content to respond to their answers</p>
    </div>
    
    <div id="upper_percent_complete">
        <div id="number">Percent Complete:<br /><div id="count">Questions: <?php echo $answered_questions; ?> / <?php echo $total; ?></div></div>
        <div id="percent"><?php echo $percent_complete; ?>%</div>
    </div>
    
    <div class="full_wide">
    
        <?php foreach ( $categories AS $category ) { 
            $answeredqs = questions::total_answered($friend_id, $category->id);
            ?>
            <div class="category_holder">
                <div class="image"><a href="<?php echo utils::url('home'); ?>friends_category/<?php echo $category->slug; ?>-<?=$friend_id?>"><img src="<?php echo utils::url('theme'); ?>images/categories/<?php echo $category->image_url; ?>" alt="<?php echo $category->title; ?>" /></a><br /></div>
                <div class="complete_bar">
                    <div class="view_link"><a href="<?php echo utils::url('home'); ?>friends_category/<?php echo $category->slug; ?>-<?php echo $friend_id; ?>">View Chapter</a></div>
                    <div class="percent"><?=number_format(100*questions::total_answered($friend_id, $category->id)/questions::count_questions_by_category($category->id),0)?>%</div>
                </div>
                <h4><strong><a href="<?php echo utils::url('home'); ?>friends_category/<?=$category->slug?>-<?=$friend_id?>"><?php echo $category->title; ?></a></strong></h4>
                <?
                $countsc = questions::count_subcategories($category->id);
                $countq = questions::count_questions_by_category($category->id);
                ?>
                <?/*<em><?if($countsc>0) echo $countsc.' Categories // ';?><?=$countq?> Question<?=($countq>1?'s':'')?></em>*/?>
                <em>Answered Questions: <?=$answeredqs?> of <?=$countq?></em>

            </div>
        <?php } ?>
    
    </div>

    <div class="remove_book_link">
        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to remove <?php echo $friend->first_name; ?>\'s book from your library? If you agree you will immediately lose access to see their story.');">
          <input type="hidden" name="friend_id" id="friend_id" value="<?php echo $friend_id; ?>" />
          <input type="hidden" name="delete" id="delete" value="delete" />
            <input type="submit" name="uninvite" value="Remove This Book from Your Library" class="" />
        </form>
    </div>

</div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>