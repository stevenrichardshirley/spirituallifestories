<?php
    session_start();
    $user = user::identify(true);
    
    define('fake_id_spiritual_book' , 99999);
    
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    
    $library = books::get_library($user->user_id);
    $books = count($library);
    $i=1;
?>

<div id="library" role="main">
<div class="width_setter">

    <div class="column50" style="width: 467px;">
        <h2><?php echo $user->first_name; ?> <?php echo $user->last_name; ?>'s Library</h2>
        <p class="descriptionText">Click on a book to get started.</p>
    </div>
    
    <div class="column50">
        <h2>Your Friend's Books</h2>
        <p class="descriptionText">
        
        When someone invites you to read their book, it will appear below. Click on a book to read their story AND add content.
    </div>

    <div id="bookcase" style="width: 982px;">
    
    <div id="bookcase_left">
    <!-- <div class="width_setter"><div id="add_a_book"><a href="/library/new-book/"><img src="<//?php echo $theme->image('button-addbook.jpg'); ?>" /></a></div></div> -->
    
    <?php 
    
    // adding the spiritual fake-book
    $new_pos = count($library);
    $library[$new_pos]->id = fake_id_spiritual_book;
    $library[$new_pos]->title='Write your Spiritual Life Story<br />(for free)';
    $library[$new_pos]->cover=1;
    $books++;
    
    
    foreach( $library as $book ) { 
    $cover = books::get_cover($book->cover);
    if ($book->id == fake_id_spiritual_book)
        $cover[0]->image_url = 'sls.png';
    if($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 9) { 
    ?>
        <div class="bookcase_shelf">
        <?php } ?>
            <?php $slug = $book->slug; ?>
                <div class="column25_book">
                    <?php if ( strlen($book->image_url) > 0 ) { ?>
                    <div class="custom_book">
                        <div class="img_holder" style="background-image: url(<?=$book->image_url?>);"></div>
                        <div class="title_box">
                        <?php if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                        <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                        <? } else { ?>
                        <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                        <?php } ?>
                        
                        </div>
                        <!-- <a href="<?php echo utils::url('home'); ?>edit_book/book-<?php echo $book->id; ?>" class="edit">EDIT</a> -->
                    </div>
                    <?php } else { ?>
                    <div class="book" style="background: url(<?php echo $theme->book($cover[0]->image_url); ?>) top left no-repeat;">
                        <?
                        if ($book->id == fake_id_spiritual_book)
                        {
                        ?>
                            <a href="http://spirituallifestories.com/register" target="_blank"><?php echo $book->title; ?></a>
                        <?
                        }
                        else
                        {
                            if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                            <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                            <? } else { ?>
                            <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                            <?
                            } 
                        }
                        ?>
                        <!-- <a href="<//?php echo utils::url('home'); ?>edit_book/book-<//?php echo $book->id; ?>" class="edit">EDIT</a> -->
                    </div>
                    <?php } ?>
                </div>
        <?php if( $i == 2 || $i == 4 || $i == 6 || $i == 8 || $i == $books ) { ?>
        </div>
    <?php } 
    $i++;
    } ?> <!-- end foreach -->
    
    
    </div> <!-- end bookcase_left -->
    
    <div id="bookcase_right">
    
    <?php
    $i = 1;
    $friends_books = books::friends($user->email);
    $friend_book_count = count($friends_books);
    
    if ($friend_book_count == 0)
    {
        print '<div class="bookcase_shelf"></div>';
    }
    
    foreach( $friends_books as $book ) { 
    $cover = books::get_cover($book->cover);
    if($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 9) { 
    ?>
        <div class="bookcase_shelf">
    <?php } ?>
            <div class="column25_book">

                <div class="book" style="background: url(<?php echo $theme->book('book3.jpg'); ?>) top left no-repeat;">
                    <a href="<?php echo utils::url('home'); ?>friends_book/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                </div>

            </div>
        <?php if( $i == 2 || $i == 4 || $i == 6 || $i == 8 ) { ?>
        </div>
    <?php } 
    $i++; 
    } ?> <!-- end foreach -->
    
    </div> <!-- end bookcase_right -->
    
    </div> <!-- end bookcase -->
    
</div> <!-- end width_setter -->

</div> <!-- end library -->

<?
$max_book_count = max($friend_book_count, $books);
if ($max_book_count > 2)
{
?>
    <div id="divScrollDown" style="overflow:hidden;position:fixed;bottom:20px;right:20px;background-color:rgba(255,255,50,0.7);padding:10px;color:black;border-radius:20px">
        <div style="padding-left:35px;padding-right:10px;height:30px;vertical-align:middle;display:table-cell"><b>Scroll down to see more books</b></div>
        <div style="position:absolute;left:10px;top:10px"><img src="<?=$theme->image('scroll_down.png')?>" height=30></div>
    </div>
    
    <script>
    $(window).scroll(function(s){
        var t = ($(window).scrollTop()+$(window).height())/$(document).height();
        if (t > 0.8)
            $('#divScrollDown').fadeOut();
        else
            $('#divScrollDown').fadeIn();
        
    });
    </script>
    
<?}?>

<?php $theme->load('footer'); ?>