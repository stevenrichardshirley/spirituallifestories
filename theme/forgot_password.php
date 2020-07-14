<?php
    session_start();    
    echo $token;
    // Validation of input
    if( isset($_POST["email"]) )
    {
    
        $token = user::insert_token($_POST['email']);
        if ( $token != false ) {
            $email = email::password_reset($_POST['email'], $token);
            if ( $email == true ) {
                $success = "We have sent an email with a link to reset your password to the email address you provided.";
            }
        }

    } // Validation end
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
?>
<div id="main" role="main">
<div class="width_setter">

<?php if( isset($login) && $login['status'] == false ) { ?>
    <div class="message_error">
        <strong>Error!</strong> Something has gone wrong and we could not log you in. Please try again.
    </div>
<?php } ?>
<?php if( isset($error) ) { ?>
    <div class="message_error">
        <?php echo $error; ?>
    </div>
<?php } ?>

    <div class="column66">
            <?php if( isset($success) ) { ?>
                <div class="message_pass">
                    <?php echo $success; ?>
                </div>
            <?php } ?>
                <h2>Reset Your Password</h2>
                <p>Enter your email address below and we will send you an email to help you reset your password.</p>                
                                
                <form method="post" action="">
                    
                    <div class="form100">
                        <input type="email" name="email" class="field100">
                    </div>
                                    
                    <div class="form100">
                        <input type="submit" name="submit" value="Submit" class="form_button" />
                    </div>
                    
                </form>
                            
            </div>
            
</div> <!-- width_setter -->
</div> <!-- main -->

<?php $theme->load('footer'); ?>