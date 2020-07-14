<?php 
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $content = page::content();
    $theme->load('header');
    $theme->load('header_content');
?>
<div id="content_holder">

    <div class="info_box shadow">
        <div class="column">
            <p><img src="<?php echo $theme->image( 'reserved_seats_diag.jpg' ); ?>" alt="Reserved Seat Diagram" /></p>
            <p>
            <strong><?php echo $content[7]->title; ?></strong><br />
            <?php echo utils::limit_no_strip($content[7]->content, 400); ?>
            </p>
        </div>
        
        <div class="column">
            <p><img src="<?php echo $theme->image( 'ticket_pricing.jpg' ); ?>" alt="Reserved Seat Diagram" /></p>
            <p>
            <strong><?php echo $content[8]->title; ?></strong><br />
            <?php echo utils::limit_no_strip($content[8]->content, 400); ?>
            </p>
        </div>
        
    </div>
    
    <div class="info_box shadow">
        <div class="column_no_pad">
        <div id="cafe_menu"><a href="" name="bar_cafe">Cafe Menu</a></div><br />
        <div id="bar_and_lounge"><a href="">Bar and Lounge</a></div>
        </div>
        <div class="column">
            <strong><?php echo $content[9]->title; ?></strong><br />
            <?php echo utils::limit_no_strip($content[9]->content, 800); ?>
        </div>
    </div>
    
    <div class="info_box shadow">
        <a name="about"><img src="<?php echo $theme->image( 'cinema_picture.jpg' ); ?>" alt="Violet Crown Cinema" /></a>
        <div class="double_column">
            <strong><?php echo $content[10]->title; ?></strong><br />
            <?php echo utils::limit_no_strip($content[10]->content, 800); ?>
        </div>
    </div>
    
    <div class="info_box shadow">
        <div id="parking"><a name="parking" href="">Free Parking</a></div>
        <div class="column">
            <strong><?php echo $content[11]->title; ?></strong><br />
            <?php echo utils::limit_no_strip($content[11]->content, 500); ?>
        </div>
        <div class="right">
            <img src="<?php echo $theme->image( 'the_number_four.jpg' ); ?>" alt="4 Hour Validation" />
        </div>
    </div>
    
    
    <div id="back_to_top"><a href="<?php echo utils::url('home'); ?>about/">Back to Top</a></div>
    
    <div id="show_all_films_wide"><a href="<?php echo utils::url('home'); ?>movies/">Show All Films</a></div>
    

</div>
<?php $theme->load('footer'); ?>