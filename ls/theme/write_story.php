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
            <h2>Write Your Story</h2>
            <p>You have a story to tell and pass on – Start today!</p>
            <p>Your memories and legacy will inspire and enrich others.</p>
            <p>LifeStories.com guides you in writing your life story. This easy, intuitive, comprehensive and a fun website allows you to:</p>
            <ul>
            <li><strong>Compose</strong><br />Capture your memories and write your life story using questions. We provide over 1,000 questions to help you!  These questions help stimulate your memory and cover your different life stages.   You can answer all of them or just a partial list.
            </li>
            <li><strong>Collaborate</strong><br />Invite others to read your story AND they can add content to your story.  It's easy and fun to do.  After all life is about those experiences and events with others.  So invite them to enrich your story.  
            </li>
            <li><strong>Contribute</strong><br />Your journey is something beautiful to share with your family and friends. Really... is there anything more important to pass on to generations that follow?
            </li>
            </ul>
            <p>Your Life and Your Story will be honored and treasured for generations to come.</p>
            <p>Start today!  Begin filling in the pieces of your journey free for 30 days. <a href="<?php echo utils::url('home'); ?>register/">Sign up now</a>.</p>
        </div>
    
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>