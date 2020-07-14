<?php
    session_start();
    $user = user::identify();
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
?>
<script>
$(document).ready(function(){$('.box_readmore').fancybox({type:'inline'})});
</script>
<style>
#divTeam{position:relative;margin-top:10px}
#divTeam div {display:inline-block;vertical-align:top;margin:10px;text-align:center;width:260px;}
#divTeam div img {margin:auto;display:block;width:130px}
</style>

<div id="main" role="main">

    <div class="width_setter">
    <div id="content_holder">

        <div class="column66">
            <h2>About Us</h2>
            
            <p>With our professional and passionate workmanship, Life Stories  Company has assisted many individuals, families, and companies in documenting and preserving their story and legacy, allowing them to share these timeless and priceless treasures with generations to come.</p>
            <p>Our team of professionals consists of interviewers, writers, editors, graphic artists, archivists, photographers, videographers, and layout specialists.</p>
            
            
            <div id="divTeam">
                <div >
                    <img src="<?=utils::url('theme')?>/images/team/chad.jpg">
                    <b>Chad Harbour, Founder</b><br />“A happy customer makes a happy team!”<br /><a href="#divChad" class="box_readmore">read more...</a>
                </div>
                <div >
                    <img src="<?=utils::url('theme')?>/images/team/tim.jpg">
                    <b>Tim B., Chief Editor</b><br />“I’ll make it shine like the stars!”</p>
                </div>
    
                <div>
                    <img src="<?=utils::url('theme')?>/images/team/holly.jpg">
                    <b>Holly K., Digital Imagery / Photography</b><br>“Hold that!  Just one more…!”
                </div>

                <div>
                    <img src="<?=utils::url('theme')?>/images/team/david.jpg">
                    <b>David F., Graphics, Covers and Layout </b><br>“Let’s pretty this up.”
                </div>

                <div>
                    <img src="<?=utils::url('theme')?>/images/team/mark.jpg">
                    <b>Mark H., Digital Media</b><br />“Expanding the digital footprint is where it’s at.”
                </div>

                <div>
                    <img src="<?=utils::url('theme')?>/images/team/kyle.jpg">
                    <b>Kyle A., Video</b><br />“Lights!  Camera!  Action!”
                </div>

                <div>
                    <img src="<?=utils::url('theme')?>/images/team/becky.jpg">
                    <b>Becky H., Administration</b><br />“Let’s be organized and stay organized.”
                </div>
                
                <div>
                    <img src="<?=utils::url('theme')?>/images/team/ceniz.jpg">
                    <b>Luiz C., Web Master</b><br />“We can do that.”
                </div>
            </div>
         </div>
    
        <div class="column33">
            <?php $theme->load('sidebar'); ?>
        </div>
    </div>
    
</div>

<div style="display:none">
<div id="divChad" style="width:700px">
    <b>About Chad Harbour</b>

    <p>Chad Harbour has always been passionate about documenting history.  At 17 years old, he began his photo-journalism adventure.  With a camera given to him upon his mother’s passing, he began documenting his life through photographs, eventually creating over 200 scrapbooks and digital scrapbooks.</p>

    <p>He began journaling when his first child was born. Twenty years later he had written over 6,000 pages in his daily journal. Unusual, yes, but this passion for documenting life evolved into an ardent interest to help others do the same.</p>

    <p>Chad believes in the need for inter-generational social exchange-passing on our knowledge, character qualities, values, wisdom, morality, faith, and life lessons to generations to come for the betterment of our nation, communities, and families.</p>

    <p>He is also a popular speaker and discusses how anyone can write his or her life story, providing simple tips and examples to follow. </p> 

    <p>His website, www.SpiritualLifeStories.com, allows users to write their spiritual story and also invite others to read and collaborate. The process is unique, innovative, and interactive.  It’s the only place where people of faith can document and share their spiritual legacy.  It’s free, fun, and so needed.</p>

    <p>Chad has 30 years of telecom executive experience.  In his last corporate assignment, he was Chief Operating Officer of a $100 million company. He retired in 2005 to focus on his passion: penning memoirs, family-history, and company-history books.</p>

    <p>In Chad’s book <i>How to Write Your Life Story in 5 Minutes a Day</i>, he provides direction to write daily about one’s life-to capture those little moments “that if written down, become special.”  In addition, his blog entries encourage readers to engage in this worthy endeavor. His motto-“5 MINUTES A DAY” of journaling-will create 120 pages a year.  What a legacy that builds for you to pass on!</p>

    <p>Chad resides in Dallas, Texas, with his wife and two children.</p>

    <p>214-288-2262<br /><a href="mailto:chad@lifestories.com">Chad@LifeStories.com</a></p>
</div>
</div>


<?php $theme->load('footer'); ?>