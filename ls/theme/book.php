<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
    $book = books::get_book($user->user_id, $page->slug);
    $book = $book[0];
?>

<div id="main" role="main">
    <div class="width_setter">
    
        <h2 class="tk-freight-sans-pro"><?php echo $book->title; ?>'s Library</h2>
        <p>
        Please click on a book cover to begin your journey.
        </p>
    

    
    </div> <!-- width_setter -->

</div> <!-- main -->

<?php $theme->load('footer'); ?>