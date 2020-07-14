<?php
    session_start();
    $user = user::identify(true);
    
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
        <p class="descriptionText">We have organized the questions into chapters that represent periods of your life. <br />Click on one of the chapters below to begin telling your story.</p>
    </div>

    <div style="float:right">
        <div id="upper_percent_complete">
            <div id="number">Percent Complete:<br /><div id="count">Questions: <?php echo $answered_questions; ?> / <?php echo $total; ?></div></div>
            <div id="percent"><?php echo $percent_complete; ?>%</div>
        </div>

        <div style="clear:both;text-align:left;padding-top:10px"><a href="view">View my book</a></div>
    </div>
    
    <div class="full_wide">
    
        <?php foreach ( $categories AS $category ) { 
            $answeredqs = questions::total_answered($user->user_id, $category->id);
            $newcontent = questions::category_with_questions_answered_by_friend($user->user_id, $category->id);
            ?>
            <div class="category_holder">
                <div class="image"><a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>"><img src="<?php echo utils::url('theme'); ?>images/categories/<?php echo $category->image_url; ?>" alt="<?php echo $category->title; ?>" /></a><br /></div>
                <div class="complete_bar">
                    <div class="view_link" style="position:relative">
                        <a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>">View Chapter</a>
                    </div>
                    <div class="percent"><?=number_format(100*$answeredqs/questions::count_questions_by_category($category->id),0)?>%</div>
                    
                </div>
                <h4><strong><a href="<?php echo utils::url('home'); ?>category/<?php echo $category->slug; ?>"><?php echo $category->title; ?></a></strong></h4>
                <?
                $countsc = questions::count_subcategories($category->id);
                $countq = questions::count_questions_by_category($category->id);
                ?>
                <em>Answered Questions: <?=$answeredqs?> of <?=$countq?></em>
                <?/*<?if($countsc>0) echo $countsc.' Categories // ';?><?=$countq?> Question<?=($countq>1?'s':'')?>*/?>
                
                <?if($newcontent > 0){?>
                    <div style="position:absolute;top:-10px;right:-5px;"><img src="<?php echo utils::url('theme'); ?>images/life_stories_man.png" / title="You have new content added to one of the questions on this chapter"></div>
                <?}?>

            </div>
        <?php } ?>
    
    </div>

</div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>