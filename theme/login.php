<?php
    session_start();
    $user = user::identify();
    if ( $user ) {
        utils::redirect( utils::url('library') );
    } 
    
    if ( isset($_POST['login']) ) {
        $login = user::login($_POST);
        if( $login['status'] == true ) {
            utils::redirect( utils::url('library') );
        } 
    }
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    $theme->load('header');
    $theme->load('header_content');
    $contents = $page->content;
?>
<div id="main" role="main">
<div class="width_setter">

    <div class="column66">
        
        <div class="form_title">Sign In to Your Account</div>
        
        <?php if( isset($login) && $login['status'] == false ) { ?>
            <div class="message_fail"><?php echo $login['message']; ?></div>
        <?php } ?>
    
        <form action="" method="POST"> 
        <div class="form100">
            <label for="email">Email:</label> 
            <input type="text" name="email" id="email" class="field100" value="<?=$_POST['email']?>">
        </div>  
        
        <div class="form100">
            <label for="password">Password:</label> 
            <input type="password" name="password" id="password" class="field100">
        </div>
        
        <div class="form100">
            <a href="<?php echo utils::url('home'); ?>forgot_password/">forgot password?</a>
        </div>
        
        <div class="form100">
            <input type="submit" name="login" id="login" value="Login" class="form_button">
        </div>  

        </form>

    </div>
    
    <div class="column33">
        <?php $theme->load('sidebar'); ?>
    </div>
    
</div> <!-- width_setter -->
</div> <!-- main -->

<?php $theme->load('footer'); ?>