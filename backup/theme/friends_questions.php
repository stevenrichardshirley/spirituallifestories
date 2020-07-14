<?php
    session_start();
    $user = user::identify();
    if ( !$user ) {
        utils::redirect( utils::url('home') );
    } 
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
    $friend_id = substr($page->content, -1);
    $friend = user::get_by_id($friend_id);
    
    $book_id = books::get_my_story($friend_id);

    $category = substr_replace($page->content ,"",-2);

    $category = questions::get_category($category);
    $category = $category[0];
    $answered_questions = questions::total_answered($friend_id);
    $total = questions::count_questions();
    $percent_complete = ($answered_questions/$total)*100;
    $percent_complete = number_format($percent_complete, 0);

?>

<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><a href="<?php echo utils::url('home'); ?>friends_book/book-<?php echo $book_id[0]->id; ?>"><?php echo $friend->first_name; ?>'s spiritual life story</a></li>
            <li><?php echo $category->title; ?></li>
        </ul>
    </div>
</div>

<div id="main" role="main">

<div class="width_setter">

    <div id="questions_holder">
    <div class="column75">
        <h2><?php echo $category->title; ?></h2>
        <?php if ( $category->description != '' ) { ?>
            <div id="description"><?php echo $category->description; ?></div>
        <?php } else { ?>
            <div id="description"><em>No description entered.</em></div>
        <?php } ?>
    </div>
    <div class="column25">
    <div id="upper_percent_complete">
        <div id="number">Percent Complete:<br /><div id="count"><?php echo $answered_questions; ?> / <?php echo $total; ?></div></div>
        <div id="percent"><?php echo $percent_complete; ?>%</div>
    </div>
    </div>
    <div class="column75">
        <?php
        if ( questions::count_subcategories($category->id) > 0 ) {
        $subs = questions::get_subcategories($category->id);
            foreach ( $subs as $sub ) {
        ?>
        
            <h3><?php echo $sub->title; ?></h3>
            <ul>
            <?php
                foreach (questions::get_questions_by_subcategory($sub->id) as $question) {
                $answered = questions::question_answered($friend_id, $question->id);
            
                if ( $answered > 0 ) {
            ?>
                <li class="answered"><a href="<?php echo utils::url('home'); ?>friends_question/question-<?php echo $question->id; ?>-<?php echo $friend_id; ?>/"><?php echo $question->content; ?></a></li>
                
                <?php } ?>
                    
            <?php } ?>
            </ul>
            <?php
            $total_answered_for_sub = questions::answered_by_subcategory($friend_id, $sub->id);
            if ($total_answered_for_sub == 0) {
            ?>
            <ul><li><em>Your friend has not answered any questions for this subcategory.</em></li></ul>
            <?php } ?>

            <?php } ?>
            
        <?php } else { ?>
        
            <ul>
            <?php
                $questions = questions::get_questions();
                foreach (questions::get_questions_by_category($category->id) as $question) {
                $answered = questions::question_answered($friend_id, $question->id);
            
                if ( $answered > 0 ) {
            ?>
                <li class="answered"><a href="<?php echo utils::url('home'); ?>friends_question/question-<?php echo $question->id; ?>-<?php echo $friend_id; ?>/" class="answered"><?php echo $question->content; ?></a></li>
                
                <?php } ?>
                
            <?php } ?>
            </ul>
        
        
        <?php } ?>
        
    </div>
    
    
    
    <div class="column25">
    <h4>Family &amp; Friends Helping Answer Questions in this Section:</h4>
    <p><em><strong>Oh no!</strong> You have not invited anyone to chime in on this section.</em></p>
    <a href="<?php echo utils::url('home'); ?>invite/" class="button">Invite Someone</a>
    </div>

</div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>