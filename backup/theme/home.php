<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
?>
<div id="container">
    <header>
        <div class="width_setter">
            <div id="logo"><h1><a href="<?php echo utils::url('home'); ?>">Life Stories</a></h1></div>
            <div id="banner_headline">
<!--
                <p>Write the story that they will never put down... yours!</p>
                <div id="sign_up_button"><a href="<//?php echo utils::url('home'); ?>register/">create your free account now!</a></div>
-->
            </div>
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
    </header>
    
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
    
<div id="main" role="main">
<div class="width_setter">

    <div class="column33">
        <div class="titlebar">Learn More</div>
        <a href="<?php echo utils::url('home'); ?>write_your_story"><img src="<?php echo utils::url('theme'); ?>images/home_column-learn_more.jpg" alt="Learn More" /></a>
        <p>
        Your spiritual life story, you legacy memoir, will motivate, inspire and enrich your family and friends for generations to come.  Contribute your story for their betterment.
        </p>
        <a href="<?php echo utils::url('home'); ?>write_your_story" class="more">Learn More</a>
    </div>
    
    <div class="column33">
        <div class="titlebar">Testimonials</div>
        <a href="<?php echo utils::url('home'); ?>testimonials"><img src="<?php echo utils::url('theme'); ?>images/home_column-testimonials.jpg" alt="Testimonials" /></a>
        <p>
        See customer comments, testimonials.
        </p>
        <a href="<?php echo utils::url('home'); ?>testimonials" class="more">View More</a>
    </div>
    
    <div class="column33">
        <div class="titlebar">Sign Up to Get Started</div>
        <a href="<?php echo utils::url('home'); ?>register"><img src="<?php echo utils::url('theme'); ?>images/home_column-sign_up.jpg" alt="Sign Up to Get Started" /></a>
        <p>
        You’ve thought about writing your spiritual life story, but it seemed too daunting.  Well now you can - we have built this site for you.  It’s easy and fun! 
        </p>
        <a href="<?php echo utils::url('home'); ?>register" class="more">Sign Up Now</a>
    </div>

</div> <!-- width_setter -->
</div> <!-- main -->

<?php $theme->load('footer'); ?>