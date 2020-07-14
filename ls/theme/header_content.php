<?php
    $theme = new theme();
    $user = user::identify();
?>
<div class="width_setter">
    <div id="logo"><h1><a href="<?php echo utils::url('home'); ?>">Life Stories</a></h1></div>

    <div id="account_bar">
        <ul>
            <?php if ( $user ) { ?>
                <li><a href="<?php echo utils::url('home'); ?>myaccount/" class="name">Welcome, <?php echo $user->first_name; ?></a></li>
                <li><a href="<?php echo utils::url('home'); ?>library/">Library</a></li>
                <li><a href="<?php echo utils::url('home'); ?>invite/">Invite Others</a></li>
                <li><a href="<?php echo utils::url('home'); ?>logout/">Logout</a></li>
            <?php } else { ?>
                <li><a href="<?php echo utils::url('home'); ?>login/">Sign In</a></li>
                <li><a href="<?php echo utils::url('home'); ?>register/">Sign Up Now</a></li>
            <?php } ?>
        </ul>

        <?php 
        if ( $user ) { 
            if (!$user->is_activated_user())
            {
                $days_free = $user->activated_free_days();
            ?>
                <div style="clear:both"></div>
                <div style="line-height:100%;clear:both;background-color:#FFA;color:#000;text-align:center;position:relative;margin-top:3px;padding:5px">
                    <?if($days_free > 0){?>
                        Your free account will expire in <b><?=$days_free?> day<?=($days_free>1?'s':'')?></b>. <a href="<?php echo utils::url('home'); ?>activate/">Click here to active it!</a>
                    <?}else{?>
                        Your free account <b>is expired</b>. <a href="<?php echo utils::url('home'); ?>activate/">Click here to active it!</a>
                    <?}?>
                </div>
        <?
            }
        }?>

    </div>
    
    
            
</div>
    
    <?php if ( $user ) {} else { ?>
        <nav>
            <div id="top_nav">
                <ul>
                    <li id="register"><a href="<?php echo utils::url('home'); ?>register">Get Started Today</a></li>
                    <li id="you_write_story"><a href="<?php echo utils::url('home'); ?>write_your_story">You Write Your Story</a></li>
                    <li id="testimonials"><a href="<?php echo utils::url('home'); ?>testimonials">Testimonials</a></li>
                    <li id="about_us"><a href="<?php echo utils::url('home'); ?>about">About Us</a></li>
                    <li id="we_write_story"><a href="http://www.lifestoriescompany.com" target="_blank">We Write Your Story</a></li>
                    <li id="contact_us"><a href="<?php echo utils::url('home'); ?>contact">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    <?php } ?>
