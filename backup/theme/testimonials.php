<?php
    session_start();
    $user = user::identify();
    
    // Validation of input
    if( isset($_POST["email"]) )
    {
    
        $email = email::send_testimonial($_POST);
        if ( $email == true ) {
            $success = "Your message has been sent. We will get back to you soon!";
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
        <div id="content_holder">

            <div class="column66">
                <h2>Testimonials</h2>
                
                <?php if( isset($error) ) { ?><div class="message_fail"><?php echo $error; ?></div> <?php } ?>
					 <?php if( isset($success) ) { ?><div class="message_pass"><?php echo $success; ?></div> <?php } ?>
					 
                <div class="testimonial">
                    <div class="testimonial_content">This is so great.  I have been writing my spiritual journey on your site and sharing it with my friends.  They also have added content!  I can't wait to print this out as a book and give it to my kids and friends!  Many thanks!</div>   
                    <div class="testimonial_author">- Ray H.</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">I have been looking for a place to do this and now you have the platform.  Nicely done.  Very well thought through.  God bless you.</div>   
                    <div class="testimonial_author">- Susan, Houston, Texas</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">I introduced this to my mother and she is so excited.  She's now starting a Bible study class around your website.  Thought you should know.  Love it!!!!</div>   
                    <div class="testimonial_author">- Nicole, Boston, MA</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">I have been using this now for a month and it's really wonderful.  It had helped me in thinking through my Christian beliefs and I look forward to printing this as a book to share with my kids.  Good job!  BTW – I'm telling everyone about it!</div>   
                    <div class="testimonial_author">- Mr. B., San Jose, CA.</div>
                </div>
            </div>
       
            <div class="column33">
                <h3>Send Us Your Comments</h3>
                <p>Do send us your comments and your testimonials.  We'll add it to our ever-growing list so others can appreciate this powerful purpose.</p>
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
                        <label for="message">Your Comments</label>
                        <textarea name="message" id="message" style="width:90%;"></textarea>
                    </div>
                    <div class="form50">
                        <input type="submit" name="submit" id="submit" value="Submit Your Testimonial" class="form_button" />
                    </div>
                </form>
          </div>
        </div>
    </div>
</div>

<?php $theme->load('footer'); ?>