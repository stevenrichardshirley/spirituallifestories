<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

        <div class="column66">
            <h2>Free Resources</h2>
            <p>Were glad you're here!</p>
            <p>We want to provide you some FREE tools to further assist you.</p>
            <h3>FREE: eBook</h3>
            <p>Your spiritual life story in 5 Minutes a Day!</p>
            <ul>
                <li><a href="">Click to download your free copy</a></li>
                <li><a href="">Click here to view your free copy</a></li>
            </ul>
            
            <p>This book encourages and assists those interested in writing their spiritual life story. Being passionate about helping others write their spiritual life story, we wanted to provide you a tutorial how you can do this yourself! This book shows you the way and provides many helpful suggestions, and will inspire and motivate you.  <a href="">Download it now.</a></p>

        </div>
    
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>