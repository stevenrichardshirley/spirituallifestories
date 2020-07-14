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
                    <div class="testimonial_content">Your website, LifeStories.com, is just simply fantastic. I have been wanting to write my story for years but didn’t have a clue how to start. Your questions have provided me the motivation I needed! Thank you very much.</div>
                    <div class="testimonial_author">- Janet O., Philadelphia</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">What is really amazing about your website is how I can “Invited Others” and then they can read my book, <b><u>AND</u></b> add content to my questions. It’s very cool and I so appreciate your help. I’m a very satisfied customer</div>   
                    <div class="testimonial_author">- Robert L., Des Moines, IA</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">I have been using your website every day to write my life story. I have found it very easy to use and helpful. I thank you very, very much for your efforts.  Well done!</div>   
                    <div class="testimonial_author">- Pat K, Princeton, NJ</div>
                </div>
             
                <div class="testimonial">
                    <div class="testimonial_content">What a great website! I especially enjoy inviting my family to read my story. And, when they sign up I can see their book in my library area under <b><u>“Friends’ Books”</u></b>, very innovative and helpful. Now we can all share our stories! Like you say... we are “building a bridge” to connect our generations. It’s wonderful.</div>   
                    <div class="testimonial_author">- Craig C., Portland, OR</div>
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