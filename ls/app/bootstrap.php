<?php
require 'autoload.php';
spl_autoload_register( 'bbc_autoload' );

// Turn off all error reporting
// error_reporting(0);

define('IS_DEV', false);


define('ROOT_DIRECTORY', str_replace('/index.php','',$_SERVER['PHP_SELF'] )); 

if (IS_DEV == 'true') {
	define('MEDIA_URL', 'http://betabit.com.br/ls');
	define('STATIC_MEDIA_URL', 'http://betabit.com.br/ls');
	define('BASE_URL', 'http://betabit.com.br/ls');
	define('DOCUMENT_DOMAIN', 'http://betabit.com.br/ls');
	define('APP_PATH', '');                             
} else {	
	define('MEDIA_URL', 'http://lifestories.com');
	define('STATIC_MEDIA_URL', 'http://lifestories.com');
	define('BASE_URL', 'http://lifestories.com');
	define('DOCUMENT_DOMAIN', 'http://lifestories.com');
	define('APP_PATH', '');
}
# Media related constants

define('MEDIA_IMG_PATH', MEDIA_URL . 'images/');
define('MEDIA_CSS_PATH', MEDIA_URL . 'styles/');
define('MEDIA_JS_PATH', MEDIA_URL . 'scripts/');
define('THEME_PATH', APP_PATH . 'theme/');
define('ADMIN_THEME_PATH', APP_PATH . 'admin/theme/');
define('SSL_ENABLED', false);

define('total_photos_limit', 150);
?>