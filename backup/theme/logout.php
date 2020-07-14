<?php
    session_start();
    $user = user::identify();
    if ( $user ) {
        user::logout();
        utils::redirect( utils::url('home') );
    } else { 
        utils::redirect( utils::url('home') );
    }
?>