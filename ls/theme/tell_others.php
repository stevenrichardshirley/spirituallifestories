<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    // Validation of input
    if( isset($_POST["email"]) )
    {
    
        $email = email::tell_others($_POST);
        if ( $email === true ) {
            $success = "Your message has been sent!";
        }

    } // Validation end
    
    
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

        <div class="column66">
            <h2>Tell Others</h2>
            <?php if( isset($error) ) { ?><div class="message_fail"><?php echo $error; ?></div> <?php } ?>
            <?php if( isset($success) ) { ?><div class="message_pass"><?php echo $success; ?></div> <?php } ?>
            
            <p>
            Nothing is more important then the legacy of your spiritual life story. Spread the good news to your friends and family and help them get started telling their story.
            </p>
            
            <form method="post" action="">
                <div class="form50">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" class="field50" />
                </div>
                <div class="form50">
                    <label for="email">Your Email</label>
                    <input type="text" name="email" id="email" class="field50" />
                </div>
                <div class="form50">
                    <label for="email">Friend's Email</label>
                    <input type="text" name="friends_email" id="friends_email" class="field50" />
                </div>
                <div class="form100">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" rows="6">Check out this great website where you can write your spiritual life story, share it with others and have friends and family add content and collaborate with you. I'm on it and it's greatness!</textarea>
                </div>
                <div class="form50">
                    <input type="submit" name="submit" id="submit" value="Sent it!" class="form_button" />
                </div>
            </form>
            
        </div>
    
        <div class="column33">
            <h3>Don't Have An Account?</h3>
            <p>Creating an account is easy. Start writing your story today by <a href="<?php echo utils::url('home'); ?>register">signing up for an account here</a>.</p>
        </div>
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>