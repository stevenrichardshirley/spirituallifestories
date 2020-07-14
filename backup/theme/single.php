<?php 
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    $contents = $page->content;
?>
<div class="width_setter">
<div id="content_holder">
    <?php $theme->load('sidebar'); ?>
    <div id="secondary_content_column">
        <div class="books">
            <h3><?php echo $contents['name']; ?></h3>
            <?php echo $contents['content']; ?>
        </div>
    </div>
</div>
</div>
</div>
<?php $theme->load('footer'); ?>