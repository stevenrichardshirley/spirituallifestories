<?php
    session_start();
    // Validation of input
    if( isset($_POST["email"]) )
    {
        if( empty($_POST["email"]) || empty($_POST["password"]) ) 
            $error = "You have to choose a username and a password";
        else if (strlen($_POST['code'])>0)
            $error = "Error trying to register";
        else
        {
            $register = user::create( $_POST );

            if(!$register)
                $error = $register['message'];
            else
            {
                $email = $register['email'];
                $user = user::get_user($email);
                $books = books::make_library($user[0]->user_id, $user[0]->first_name .' '. $user[0]->last_name);
                $welcome = email::welcome($user[0]->email,$user[0]->first_name);
                
                $login = user::login($_POST);
                if( $login['status'] == true ) {
                    utils::redirect( utils::url('library') );
                }
            }
        }

    } // Validation end
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    list($x, $perm_id) = explode('-', $page->content);
    $perm_id = intval($perm_id);
    
?>
<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

    <div class="column66">
        
        <div class="form_title">Sign up for an Account</div>
        <p>We are glad you are here. Sign up today and let’s get started! Your legacy awaits you.</p>
        
        <?php if( isset($error) ) { ?><div class="message_fail"><?php echo $error; ?></div> <?php } ?>
        <?php if( isset($success) ) { ?><div class="message_pass"><?php echo $success; ?></div> <?php } ?>
        
        <form method="post" action="">
        <input type="hidden" name="perm_id" value="<?=$perm_id?>">
            
        <div class="form50">
            <label for="first_name">First Name:</label><br />
            <input type="text" name="first_name" id="first_name" class="field50" tabindex="1" />
        </div>  
        
        <div class="form50">
            <label for="last_name">Last Name:</label><br />
            <input type="text" name="last_name" id="last_name" class="field50" tabindex="2" />
        </div>  
        
        <div class="form100">
            <label for="email">Email:</label><br />
            <input type="text" name="email" id="email" class="field100" tabindex="3" />
        </div>
        
        <div class="form100">
            <label for="password">Password:</label><br />
            <input type="password" name="password" id="password" class="field100" tabindex="4" />
        </div>  
        
        <div class="form100">
            <label for="password_confirm">Confirm Password:</label><br />
            <input type="password" name="password_confirm" id="password_confirm" class="field100" tabindex="5" />
        </div>
        
        <div class="form100">
            <input type="checkbox" name="agree_terms" value="TRUE" minchecked="1">
            By submitting this form, you confirm that you have read and agree to the <a href="<?php echo utils::url('home');?>terms_and_conditions/" target="_blank">Terms and Conditions</a> of this site (You must check the box in order to register)
        </div>
        
        <div class="form100">
            <input type="submit" name="submit" value="Register" class="form_button" />
        </div>
        
        
        <div style="position:absolute;left:-10000px"><input type="text" name="code"></div>
        

        </form>

    </div>
    
    <div class="column33">
        <h3>Already have an account?</h3>
        <p>If you've already signed up for an account, you can <a href="<?php echo utils::url('home'); ?>login">click here to login</a></p>
    </div>

    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>