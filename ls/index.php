<?php 
require_once('app/bootstrap.php'); 
$theme = new theme();
$page = page::dispatch();
if( $page->content == false ) {
	$theme->template( 'home' );
} else {
	$theme->template( $page->template );
}
?>
