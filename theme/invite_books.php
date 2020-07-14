<?php
    session_start();
    $user = user::identify(true);
    
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

    <h2>Invite Users by Book</h2>
    <p class="descriptionText">
        Click on a book's "Invite" blue button and you can invite others. <br />
        They will receive an automated email inviting them from you.

    </p>

    </div> <!-- width_setter -->

    <div class="width_setter">
        <?php foreach( $library as $book ) { 
            $cover = books::get_cover($book->cover);
            if($i == 1 || $i == 5 || $i == 9 || $i == 13) { 
        ?>
            <div class="content_row">
                <?php } ?>
                    <?php $slug = $book->slug; ?>
                        <div class="column25_book">
                            <?php if ( strlen($book->image_url) > 0 ) { ?>
                            <div class="custom_book">
                                <div class="img_holder" style="background-image: url(/media/covers/<?php echo $book->image_url; ?>);"></div>
                                <div class="title_box">
                                <?php if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                                <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                                <? } else { ?>
                                <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                                <?php } ?>
                                
                                </div>
                                <a href="<?php echo utils::url('home'); ?>invite/book-<?php echo $book->id; ?>" class="edit">INVITE</a>
                            </div>
                            <?php } else { ?>
                            <div class="book" style="background: url(<?php echo $theme->book($cover[0]->image_url); ?>) top left no-repeat;">
                                <?php if ( $slug == 'about-me-and-my-favorites' || $slug == 'my-journal' || $slug == 'my-story' ) { ?>
                                <a href="<?php echo utils::url('home'); ?><?php if ( $slug == 'about-me-and-my-favorites' ) { echo 'category'; } else { echo 'library'; } ?>/<?php echo $book->slug; ?>"><?php echo $book->title; ?></a>
                                <? } else { ?>
                                <a href="<?php echo utils::url('home'); ?><?php echo 'library'; ?>/book-<?php echo $book->id; ?>"><?php echo $book->title; ?></a>
                                <?php } ?>
                                <a href="<?php echo utils::url('home'); ?>invite/book-<?php echo $book->id; ?>" class="edit">INVITE</a>
                            </div>
                            <?php } ?>
                            <div class="full_wide invited_users">
                            <strong>Invited Users</strong>
                            <ul>
                            <?php
                            $invited = friends::invited($user->user_id, $book->id);
                            foreach ( $invited as $person) {
                            ?>
                            <li id="liUserInvited_<?=$book->id?>_<?=$person->id?>">
                                <?php echo $person->name;?> &nbsp;/&nbsp; <?php echo $person->relation;?><br />
                                <a href="<?=utils::url('home'); ?>invite/book-<?=$book->id?>-<?=$person->id?>">Edit</a>&nbsp;|
                                <a href="javascript: onReSendInvite(<?=$book->id?>,<?=$person->id?>)" id="aUserResend_<?=$book->id?>_<?=$person->id?>">Re-send</a>&nbsp;|
                                <a href="javascript: onUnInvite(<?=$book->id?>,<?=$person->id?>)" id="aUserInvited_<?=$book->id?>_<?=$person->id?>">Uninvite</a>
                            </li>
                            <?php } ?>
                            </ul>
                            </div>
                        </div>
                <?php if( $i == 4 || $i == 8 || $i == 12 || $i == $books ) { ?>
            </div> <!-- end content_row -->
        <?php } 
        $i++;
        } ?> <!-- end foreach -->
    </div>
</div> <!-- end library -->

<script>
function onReSendInvite(book, person)
{
    jQuery('#aUserResend_'+book+'_'+person).addClass('waitingLink').html('(wait...)');
    jQuery.post('<?=utils::url('home'); ?>invite/book-'+book+'-'+person, {resend:1})
    .done(function(s){
        alert('Invite e-mail has been sent');
        jQuery('#aUserResend_'+book+'_'+person).removeClass('waitingLink').html('Re-send');
    });
}

function onUnInvite(book, person)
{
    if (confirm('Uninvite this person?'))
    {
        jQuery('#aUserInvited_'+book+'_'+person).css('color','#555').css('text-decoration','none').html('(wait...)');
        jQuery.post('<?=utils::url('home'); ?>invite/book-'+book+'-'+person, {uninvite:1})
        .done(function(s){
            jQuery('#liUserInvited_'+book+'_'+person).fadeOut();
        });
    }
}

</script>

<?php $theme->load('footer'); ?>