<?php
    $theme = new theme();
    $user = user::identify();
?>
<div class="width_setter">
    <div id="logo"><h1><a href="<?php echo utils::url('home'); ?>">Life Stories</a></h1></div>

    <div id="account_bar">
        <ul>
            <?php if ( $user ) { ?>
                <li><a href="<?php echo utils::url('home'); ?>" class="name">Welcome, <?php echo $user->first_name; ?></a></li>
                <li><a href="<?php echo utils::url('home'); ?>library/">Library</a></li>
                <li><a href="<?php echo utils::url('home'); ?>invite/">Invite Others</a></li>
                <li><a href="<?php echo utils::url('home'); ?>logout/">Logout</a></li>
            <?php } else { ?>
                <li><a href="<?php echo utils::url('home'); ?>login/">Sign In</a></li>
                <li><a href="<?php echo utils::url('home'); ?>register/">Register for an Account</a></li>
            <?php } ?>
        </ul>
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
                    <li id="we_write_story"><a href="<?php echo utils::url('home'); ?>we_write_your_story">We Write Your Story</a></li>
                    <li id="contact_us"><a href="<?php echo utils::url('home'); ?>contact">Contact Us</a></li>
                </ul>
            </div>
        </nav>
    <?php } ?>
