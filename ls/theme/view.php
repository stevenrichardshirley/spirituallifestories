<?php
    session_start();
    $user = user::identify(true);

    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');

    $library = books::get_library($user->user_id);

    $qr = mysql_query("SELECT qc.image_url as CategoryImage, qc.title as Category, qsc.title as SubCategory, q.content as Question, qa.content as Answer, qp.image_url as QuestionImage 
                        FROM question_answers qa inner join questions q on qa.question_id=q.id
                                inner join question_categories qc on q.category=qc.id
                                left join question_subcategories qsc on q.subcategory=qsc.id
                                left join question_photos qp on qp.question_id=q.id and qp.user_id={$user->user_id}
                                    where qa.user_id={$user->user_id} ORDER BY qc.`order`, qsc.`order`");
    $book_body = array();
    while ($obj = mysql_fetch_object($qr))
    {
        unset($element);
        if ($obj->Category != $oldcat)
        {
            $book_body[] = array('type'=>'category', 'title'=>$obj->Category, 'image'=>$obj->CategoryImage);
            $oldcat = $obj->Category;
        }
        $book_body[] = array('type'=>'qa', 'question'=>$obj->Question, 'answer'=>strip_tags($obj->Answer), 'image'=>$obj->QuestionImage);
    }
    
?>

<script type="text/javascript" src="<?=utils::url('theme')?>/js/turn4/extras/modernizr.2.5.3.min.js"></script>


<div id="main" role="main">
    <div class="width_setter" style="min-height:600px;">

            <div class="flipbook-viewport">
                <div class="container">
                    
                    <div class="flipbook">
                        <div class="page hard" style="background-image:url('<?=utils::url('theme')?>/images/books/book1.jpg'); background-size:100% auto; background-position:center center">
                            <div style="font-size:43px;line-height:130%;color:white;text-align:center;padding:100px"><?=$library[0]->title?></div>
                            
                            <div style="position:absolute;bottom:10px;width:100%;text-align:left;margin-left:10px"><img src="<?=utils::url('theme')?>/images/life_stories_logo.png" width=130></div>
                            
                        </div>
                        
                        <?
                        for ($i=0;$i<count($book_body);$i++)
                        {
                        ?>
                            <div class="page <?=($i%2==0?'even':'odd')?>" style="">
                                <div class="content">
                                    <?
                                    if ($book_body[$i]['type'] == 'category')
                                    {
                                        $last_category = $book_body[$i]['title'];
                                    ?>         
                                        <br /><br /><br /><br /><center><img src="<?=utils::url('theme')?>images/categories/<?=$book_body[$i]['image']?>"><br /><br />
                                        <font size=5><b><?=$book_body[$i]['title']?></b></font>
                                        </center>
                                    <?
                                    }
                                    else
                                    {
                                    ?>
                                        <div style="font-size:10px;border-bottom:1px solid #999;padding-bottom:2px;margin-bottom:10px;color:#999;text-align:center">
                                            <?=$last_category?>
                                        </div>
                                        <font size=3><b><?=$book_body[$i]['question']?></b><br /><br />
                                        <i><?=$book_body[$i]['answer']?></i></font>
                                        <?if(strlen($book_body[$i]['image']) > 0 && file_exists('media/photos/'.$book_body[$i]['image'])){?>
                                            <center><br><br><img src="/media/photos/<?=$book_body[$i]['image']?>" style="max-width:300px"></center>
                                        <?}?>
                                        
                                    <?
                                    }
                                    ?>
                                    <div style="position:absolute;bottom:10px;width:100%;font-size:10px;color:#999;text-align:center"><?=($i+1)?></div>
                                </div>
                                <span class="gradient"></span>
                            </div>
                        <?}
                        
                        if ($i%2!=0) print '<div class="page odd"><span class="gradient"></span></div>';
                        ?>
                        

                        <div class="page hard">
                            <div style="position:absolute;top:200px;width:100%;text-align:center;"><img src="<?=utils::url('theme')?>/images/life_stories_logo.png" width=130></div>
                        </div>
                </div>
            </div>
    </div>

</div>

<script>

function loadApp() {
    $('.flipbook').turn({
            width:922,
            height:600,
            elevation: 50,
            gradients: true,
            autoCenter: true
    });
}

// Load the HTML4 version if there's not CSS transform
yepnope({
    test : Modernizr.csstransforms,
    yep: ['<?=utils::url('theme')?>/js/turn4/lib/turn.js'],
    nope: ['<?=utils::url('theme')?>/js/turn4/lib/turn.html4.min.js'],
    both: ['<?=utils::url('theme')?>/js/turn4/css/basic.css'],
    complete: loadApp
});

$(document).keydown(function(e){
        var previous = 37, next = 39;
        switch (e.keyCode) {
            case previous:
                $('.flipbook').turn('previous');
            break;
            case next:
                $('.flipbook').turn('next');
            break;
        }
    });
</script>                                       


<?php $theme->load('footer'); ?>