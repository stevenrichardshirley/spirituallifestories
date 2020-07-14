<?php
    session_start();
    $user = user::identify(true);


    if (isset($_POST['submit']))
    {
        $db = new db();
        if (strlen($_POST['first_name']) > 0 && strlen($_POST['last_name']) > 0 && strlen($_POST['email']) > 0)
        {
            $email = mysql_real_escape_string($_POST['email']);
            list($qt) = $db->fetchrow($db->query("SELECT count(*) FROM users WHERE email='$email' AND user_id<>".$user->user_id));
            if (intval($qt) == 0)
            {
                $_POST['user_id'] = $user->user_id;
                if ($_POST['change_pass'] == 'on')
                {
                    if (strlen($_POST['curpassword'])>0)
                    {
                        $curpassword = utils::crypt( utils::process( $_POST['curpassword'] ) );
                        if ($curpassword == $user->password)
                        {
                            if (strlen($_POST['password'])>0)
                            {
                                if ($_POST['password'] == $_POST['password_confirmation'])
                                {
                                    // fields and password
                                    $msg = user::update($_POST, $res);
                                    if ($res)
                                    {
                                        $success = $msg;
                                        $user = user::identify(true);
                                    }
                                    else
                                        $error = $msg;
                                }
                                else
                                    $error = 'Your passwords did not match';
                            }
                            else
                                $error = 'To change your password you need to enter a new one';
                        }
                        else
                            $error = 'Invalid password';
                    }
                    else
                        $error = 'To change your password you need to inform your current password';
                }
                else
                {
                    // only fields
                    $msg = user::update($_POST, $res);
                    if ($res)
                    {
                        $success = $msg;
                        $user = user::identify(true);
                    }
                    else
                        $error = $msg;
                }
            }
            else
                $error = 'The e-mail address "'.$_POST['email'].'" already exists';
            
        }
        else
            $error = 'First name, last name and e-mail are required fields';
    }
    
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
        
        <div class="form_title">My Account</div>
        <p>Keep your information updated.</p>
        
        <?php if( isset($error) ) { ?><div class="message_fail"><?php echo $error; ?></div> <?php } ?>
        <?php if( isset($success) ) { ?><div class="message_pass"><?php echo $success; ?></div> <?php } ?>
        
        <form method="post" action="">
            
        <div class="form50">
            <label for="first_name">First Name:</label><br />
            <input type="text" name="first_name" id="first_name" class="field50" tabindex="1" value="<?=$user->first_name?>"/>
        </div>  
        
        <div class="form50">
            <label for="last_name">Last Name:</label><br />
            <input type="text" name="last_name" id="last_name" class="field50" tabindex="2" value="<?=$user->last_name?>"/>
        </div>  
        
        <div class="form100">
            <label for="email">Email:</label><br />
            <input type="text" name="email" id="email" class="field100" tabindex="3" value="<?=$user->email?>"/>
        </div>
        
        <div class="form100">
            <input type="checkbox" name="change_pass" id="change_pass" value="on" minchecked="1" onclick="if(this.checked)$('#divChangePass').show();else $('#divChangePass').hide();">
            <label for="change_pass" class="normal_label">Change my password</label>
        </div>

        <div id="divChangePass" style="display:none">
            <div class="form100">
                <label for="curpassword">Current Password:</label><br />
                <input type="password" name="curpassword" id="curpassword" class="field100" tabindex="4" />
            </div>  
            
            <div class="form100">
                <label for="password">New Password:</label><br />
                <input type="password" name="password" id="password" class="field100" tabindex="5" />
            </div>
            <div class="form100">
                <label for="password_confirmation">Confirm New Password:</label><br />
                <input type="password" name="password_confirmation" id="password_confirmation" class="field100" tabindex="5" />
            </div>
        </div>
        
        
        <div class="form100" style="margin-top:20px">
            <input type="submit" name="submit" value="Save information" class="form_button" />
        </div>
        
        
        <div style="position:absolute;left:-10000px"><input type="text" name="code"></div>
        

        </form>

    </div>
    
    <div class="column33">
        <h3>What's next?</h3>
        <p>Visit your libray by <a href="<?=utils::url('home');?>library">clicking here</a> and start writing your Spiritual Story today !</p>

        <h3>Already have your story?</h3>
        <p>So now invite others to help you to improve what you wrote. Just <a href="<?=utils::url('home');?>invite">click here</a>.</p>

        <h3>Are you lost?</h3>
        <p><a href="<?=utils::url('home');?>contact">Click here</a> and write us. Well will be happy to help you writing your story.</p>

        <?
        if ($user->is_activated_user())
        {
        ?>
            <h3>Cancel my account</h3>
            <p>If you wanna cancel your annual payment subscription, click at this button below:
                <iframe src="<?=utils::url('theme')?>/integration/paypal_pay.php?unsub=1" frameborder=0 width=200 height=70 style="margin-top:10px"></iframe>
            </p>
        <?}?>
        
    </div>

    </div>
    </div>
    
</div>


<?php $theme->load('footer'); ?>