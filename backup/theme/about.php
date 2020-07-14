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
            <h2>About Us</h2>
            <p>Our passion is helping you document, preserve and share your legacy.</p>
            <p>Since 1993, our team has been assisting others in writing their life stories. Sitting down and hearing people tell their life stories is one of the single greatest joys for our team of writers. We have gained a deep understanding of the value of documenting a person's story and ensuring that their legacy is captured, preserved, shared and treasured for many generations to come.</p>
            <p>It's what we do for others and we feel extremely grateful to be helping you.</p>
            <p>Your Memories – Your Journey – Your Spiritual Life Story</p>
            <!--
<h3>Staff</h3>
            <p>Staff Coming Soon</p>
-->
        </div>
    
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>