<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
?>

<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

        <div class="column100">
            <h2>We Write Your Story</h2>
            <p>For many, writing ones life story or corporate history can be an overwhelming task. You may wish to have a professional write your story.</p>
            
            <p>That's what we do at LifeStories.com – <strong>Personal Assistance</strong></p>
            <p>For you it is extremely easy and enjoyable.  All you have to do is talk to us – how easy is that!   We do all the work.</p>
            <p>And we make this convenient for you by visiting you at your home for these interview sessions.  We have developed a highly efficient and thorough process and these interview sessions only take about two to three days.  That's all!</p>
            <p>The book we publish for you will be on average 350 pages and have photographs embedded into your book; we have published books over 600 pages.  We include all relevant life stages such as childhood years, teenage years, military service, college years, married life, children, careers, hobbies, etc.  We also include a Life Resume and a fun section titled "Naming This Book".  Your book will be highly customized and can include any additional significant topics, which are emblematic of your life.</p>
            <p>It's a very enjoyable process for you and the end result, your life story legacy autobiography, will become a treasured family heirloom to be shared for generations to come!</p>
            <p>Please <a href="<?php echo utils::url('home'); ?>contact/">contact us</a> for further information regarding how we can assist you.</p>
             
            <h3>Gifting – A treasured and heartfelt gift to give</h3>
            <p>Often a son or daughter desires to preserve a parent's or relative's life story.  What a wonderful gift to give!  Nothing is more treasured or heartfelt!  We all have a hard time gifting to others, especially our parents.  Why not provide them a gift of a lifetime – your and their legacy.</p>
            <p>It is never too late or too early to capture a life story.  You never know what tomorrow will bring.  So capture and preserve your and their legacy now! Our passion is helping you to share your legacy, your Life Story. The end product, your life story legacy book, will become a treasured family heirloom to be shared for generations to come.</p>
            <p>Our passion is helping you share your legacy.</p>
            <p>Please <a href="<?php echo utils::url('home'); ?>contact/">contact us</a> to discuss engaging and for further information.</p>
            <h3>Private Memoirs</h3>
            <p>Preserving ones history and ensuring the inter-generational transfer of human and intellectual capital is of utmost importance.  Having a life story documented enables you to pass on your legacy to future generations.  Without your life story documented, the opportunity for this knowledge transfer and maintaining your family heritage is at significant risk.</p>
            <p>At LifeStories.com we focus on capturing and documenting the memories, heritage and core values of a you- your knowledge, wisdom, morality, spirituality, patriotism, life lessons, and the people, places, events that you have experienced and which have molded you — in short, what you wish to pass on to your children and grandchildren thus ensuring your history, heritage and legacy are preserved and honored forever.</p>
            
            <p><a href="<?php echo utils::url('home'); ?>examples/">Click Here to See Some Examples</a></p>
            
            <h3>Company Histories</h3>
            
            <p>Every company needs to document and preserve its unique history and a path to the future.   That's what we can do for you.</p>
            
            <p>A company's history is a valuable asset and our focus is to document and preserve that historical asset.</p>
            
            <p>At LifeStories.com we take a comprehensive inside look at the people, how the business grew and overcame challenges.  We highlight the perspectives of customers, vendors, employees and the leadership team.  We highlight the assets of a company - its culture, traditions, core business values, strategic objectives and the character values of those associated it with.  We detail the past, the present and project a vision for its future.</p>
            
            <p>A corporate history book will motivate and inspire your company's stakeholders and prospects.</p>

            <p><a href="<?php echo utils::url('home'); ?>examples/">Click Here to See Some Examples</a></p>

        </div>
    
<!--
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
-->
    
    </div>
    </div>
    
</div>

<?php $theme->load('footer'); ?>
