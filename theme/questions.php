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
    
    $category = questions::get_category($page->content);
    $category = $category[0];
    $answered_questions = questions::total_answered($user->user_id);
    $total = questions::count_questions();
    $percent_complete = ($answered_questions/$total)*100;
    $percent_complete = number_format($percent_complete, 0);

?>

<div id="breadcrumb">
    <div class="width_setter">
        <ul>
            <li class="home"><a href="<?php echo utils::url('home'); ?>library/">Home</a></li>
            <li><a href="<?php echo utils::url('home'); ?>library/my-story/"><?php echo $user->first_name; ?>'s spiritual life story</a></li>
            <li><?php echo $category->title; ?></li>
        </ul>
    </div>
</div>

<div id="main" role="main">

<div class="width_setter">

<!--
    <div id="top_internal_banner">
        <a href="<//?php echo utils::url('home'); ?>invite/book-<//?php echo $book->id; ?>"><img src="<//?php echo utils::url('theme'); ?>images/banner-invite_to_collab.jpg" /></a>
    </div>
-->
    <div class="column75" style="min-height:370px">
    
    <div id="questions_holder">
        <h2><?php echo $category->title; ?></h2>
        <p class="descriptionText">Click on a question below to see it and answer it</p>

        
        <?php
        if ( questions::count_subcategories($category->id) > 0 ) {
        $subs = questions::get_subcategories($category->id);
            foreach ( $subs as $sub ) {
        ?>
        
            <h3><?php echo $sub->title; ?></h3>
            <ul>
            <?php
                foreach (questions::get_questions_by_subcategory($sub->id) as $question) {
                $answer = questions::get_answer($user->user_id, $question->id);
                $answered = questions::question_answered($user->user_id, $question->id);
                $friend_answered = questions::question_answered_by_friend($user->user_id, $question->id);
                
                /*
            ?>
                <li class="<?php if ( $answered > 0 && strlen($answer[0]->content) > 0 ) { echo 'answered'; } ?><?php if ( $friend_answered > 0 ) { echo 'contrib'; } ?>"><?php if ( $friend_answered > 0 ) { ?><div class="ls_man"><img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" /></div><?php } ?><a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/"><?php echo $question->content; ?></a></li>
                */

                if ( $answered > 0 && strlen(strip_tags($answer[0]->content)) > 0 ) 
                {?>
                <li class="answered<?php if ( $friend_answered > 0 ) { echo ' contrib'; } ?>">
                    <?php if ( $friend_answered > 0 ) { ?>
                        <div class="ls_man">
                            <a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/#contributions">
                                <img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" />
                            </a>
                        </div>    
                    <?php } ?>
                    <a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/" class="answered">
                        <?php echo $question->content; ?>
                    </a>
                </li>
                
                <? } else { ?>
                
                <li class="unanswered<?php if ( $friend_answered > 0 ) { echo ' contrib'; } ?>">
                    <a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/" class="unanswered">
                        <?php echo $question->content; ?>
                    </a>
                </li>
                
                <? } ?>
                
                

                
                
                                
            <?} ?>
            </ul>

            <?php } ?>
            
        <?php } else { ?>
        
            <ul>
            <?php
                $questions = questions::get_questions();
                foreach (questions::get_questions_by_category($category->id) as $question) {
                $answer = questions::get_answer($user->user_id, $question->id);
                $answered = questions::question_answered($user->user_id, $question->id);
                $friend_answered = questions::question_answered_by_friend($user->user_id, $question->id);
                if ( $answered > 0 && strlen(strip_tags($answer[0]->content)) > 0 ) {
            ?>
                <li class="answered<?php if ( $friend_answered > 0 ) { echo ' contrib'; } ?>">
                	<?php if ( $friend_answered > 0 ) { ?>
                		<div class="ls_man">
                			<a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/#contributions">
                				<img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" />
                			</a>
                		</div>	
                	<?php } ?>
                	<a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/" class="answered">
                		<?php echo $question->content; ?>
                	</a>
                </li>
                
                <?php } else { ?>
                
                <li class="unanswered<?php if ( $friend_answered > 0 ) { echo ' contrib'; } ?>">
                	<a href="<?php echo utils::url('home'); ?>questions/question-<?php echo $question->id; ?>/" class="unanswered">
                		<?php echo $question->content; ?>
                	</a>
                </li>
                
                <?php } ?>
                
            <?php } ?>
            </ul>
        
        
        <?php } ?>
        
    </div>
    
    </div>
    
    <div id="upper_percent_complete">
        <div id="number">Percent Complete:<br /><div id="count">Questions: <?php echo $answered_questions; ?> / <?php echo $total; ?></div></div>
        <div id="percent"><?php echo $percent_complete; ?>%</div>
    </div>
    
    <div style="float:right;width:200px;padding:10px 20px;background-color:#FFD;margin-top:20px">
        <div>Don't forget to "Invite Others" to read what you have written and you can add content to their story!</div>
        <div style="text-align:center"><a href="<?php echo utils::url('home'); ?>invite/" class="button" style="float:none;display:inline-block;margin:10px 0px 5px 0px">Invite Someone</a></div>
    </div>
    

    <div class="blue_man_legend" style="padding-bottom:0px">
        <img src="<?php echo utils::url('theme'); ?>images/question-answered.png" />
        <p>
            <strong>Green check mark</strong><br />
            This green check mark indictates that you have answered the question.  You can always add more to your answer.
        </p>
    </div>

    <div class="blue_man_legend" style="padding-top:0px">
    	<img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" />
    	<p>
    		<strong>Little Blue Man</strong><br />
    		This icon indicates that you have new content that has been added to your question. Only people you have invited can add contributions to your questions.
    	</p>
    	<p>
    		Click on the little blue man to see the contributions for that question.
    	</p>
    </div>


</div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>
