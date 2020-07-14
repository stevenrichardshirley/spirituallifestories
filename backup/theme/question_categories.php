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
    
    $categories = questions::get_categories();
    $answered_questions = questions::total_answered($user->user_id);
    $total = questions::count_questions();
    $percent_complete = ($answered_questions/$total)*100;
    $percent_complete = number_format($percent_complete, 0);
?>

<div id="main" role="main">

<div class="width_setter">

    <div class="column66">
        <h2>Chapters of Your Story</h2>
        <p>We have organized the questions into categories that represent periods of your life. Click on one of the photos below to begin telling your story.</p>
    </div>
    
    <div id="upper_percent_complete">
        <div id="number">Percent Complete:<br /><div id="count"><?php echo $answered_questions; ?> / <?php echo $total; ?></div></div>
        <div id="percent"><?php echo $percent_complete; ?>%</div>
    </div>
    
    <div class="full_wide">
    
        <?php foreach ( $categories AS $category ) { ?>
            <div class="category_holder">
                <div class="image"><a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>"><img src="<?php echo utils::url('theme'); ?>images/categories/<?php echo $category->image_url; ?>" alt="<?php echo $category->title; ?>" /></a><br /></div>
                <div class="complete_bar">
                    <div class="view_link"><a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>">View Chapter</a></div>
                    <div class="percent">0%</div>
                </div>
                <h4><strong><a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>"><?php echo $category->title; ?></a></strong></h4>
                <em><?php echo questions::count_subcategories($category->id); ?> Categories // <?php echo questions::count_questions_by_category($category->id); ?> Questions</em>
            </div>
        <?php } ?>
    
    </div>

</div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>