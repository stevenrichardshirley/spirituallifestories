<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
    $library = books::get_library($user->user_id);
    $books = count($library);
    $i=1;
?>

<div class="width_setter">

    <div class="column66">
        <h2>Chapters of Your Story</h2>
    </div>
    
    <div class="column33">
        0% complete
    </div>

</div>

<?php $theme->load('footer'); ?>