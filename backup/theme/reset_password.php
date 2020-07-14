<?php
    session_start();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    $slug = $page->content;
    $token = str_replace( 'token-', '', $slug);
    $verify = user::get_token($token);
    
    // Validation of input
    if( isset($_POST["password"]) )
    {
        
        $update = user::update_password($_POST);
        $success = $update;
        
        $login = user::login($_POST);
        if( $login['status'] == true ) {
            utils::redirect( utils::url('home').'library/' );
        }

    } // Validation end
    
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

        <?php if ( $verify[0]->token != '' ) { ?>
        <?php if( isset($success) ) { ?>
            <div class="message_pass">
                <?php echo $success; ?>
            </div>
        <?php } ?>
        <div class="column66">
            <h2>Choose a new password</h2>
            <p>Please enter a new password using the form below.</p>
                            
            <form method="post" action="">
                <input type="hidden" name="email" value="<?php echo $verify[0]->email; ?>" />
                                
                <div class="form100">
                    <label for="password">New Password *</label>
                    <input type="password" name="password" class="field100">
                </div>
                
                <div class="form100">
                    <label for="password_confirmation">Password Again *</label>
                    <input type="password" name="password_confirmation" class="field100">
                </div>  
                
                <div class="form100">
                    <input type="submit" name="submit" value="Change Password" class="form_button" />
                </div>
    
            </form>
                            
        </div>
        
        <?php } else { ?>
        <div class="message_error">
            The token provided does not match an active account on our site. 
        </div>
        
        <div class="column66">
            <p>
                It looks like the token provided does not match to an active account. If you are looking to reset your password you can do that by <a href="<?php echo utils::url('home');?>forgot_password/">clicking here</a>.
            </p>
        </div>
        
        <?php } ?>
        
</div> <!-- width_setter -->
</div> <!-- main -->

<?php $theme->load('footer'); ?>