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
?>

<div id="left_column">
    <h2>Welcome</h2>
</div>

<div id="right_column">
    Content Here.
</div>

<?php $theme->load('footer'); ?>