<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    // Validation of input
    if( isset($_POST["email"]) )
    {
    
        $email = email::contact_us($_POST);
        if ( $email == true ) {
            $success = "Your message has been sent. We will get back to you soon!";
        }

    } // Validation end
    
    
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

        <div class="column66">
            <h2>Contact Us</h2>
            <?php if( isset($error) ) { ?><div class="message_fail"><?php echo $error; ?></div> <?php } ?>
            <?php if( isset($success) ) { ?><div class="message_pass"><?php echo $success; ?></div> <?php } ?>
            
            <p>
            We greatly appreciate your questions and comments as we are passionate about improving our site and providing a problem-free experience.
            </p>
            
            <form method="post" action="">
                <div class="form50">
                    <label for="name">Your Name</label>
                    <input type="text" name="name" id="name" class="field50" value="<?=$user->first_name?>"/>
                </div>
                <div class="form50">
                    <label for="email">Your Email</label>
                    <input type="text" name="email" id="email" class="field50" value="<?=$user->email?>"/>
                </div>
                <div class="form100">
                    <label for="message">Message</label>
                    <textarea name="message" id="message"></textarea>
                </div>
                <div class="form50">
                    <input type="submit" name="submit" id="submit" value="Contact" class="form_button" />
                </div>
            </form>
            
        </div>
    
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>