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
        
        <div class="form_title">Activate Your Account</div>
        <p><font size=3>
        
We are glad you're ready to become a member.  <br /><br />

Just click on the Subscribe button below to sign-up for your annual subscription.<br /><br />

You will only be charged $100 annually for this service.  Our customers tell us it's "priceless!"<br /><br />

Begin writing your life story today and sharing it with those you love.<br /><br />

The team at Lifestories.com
        </font></p>
        
        <div style="padding:15px;text-align:center;font-size:18px;line-height:140%">
            <iframe src="<?=utils::url('theme')?>/integration/paypal_pay.php?userid=<?=$user->user_id?>" frameborder=0 width=200 height=70></iframe>
        </div>
        
        
        

    </div>
    
    <div class="column33">
        <h3>Any questions?</h3>
        <p><a href="<?=utils::url('home');?>contact">Click here</a> and write us. Well will be happy to help you writing your story.</p>
        
    </div>

    </div>
    </div>
    
</div>


<?php $theme->load('footer'); ?>