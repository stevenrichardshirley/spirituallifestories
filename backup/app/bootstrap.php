<?php
require 'autoload.php';
spl_autoload_register( 'bbc_autoload' );

// Turn off all error reporting
// error_reporting(0);

define('IS_DEV', false);


define('ROOT_DIRECTORY', str_replace('/index.php','',$_SERVER['PHP_SELF'] )); 

if (IS_DEV == 'true') {
	define('MEDIA_URL', 'http://sls.dev/');
	define('STATIC_MEDIA_URL', 'http://sls.dev/');
	define('BASE_URL', 'http://sls.dev');
	define('DOCUMENT_DOMAIN', 'sls.dev');
	define('APP_PATH', '/Users/benjordan/Sites/Benjo/spiritual_life_stories/');
} else {	
	define('MEDIA_URL', 'http://spirituallifestories.com');
	define('STATIC_MEDIA_URL', 'http://spirituallifestories.com');
	define('BASE_URL', 'http://spirituallifestories.com');
	define('DOCUMENT_DOMAIN', 'spirituallifestories.com');
	define('APP_PATH', '');
}
# Media related constants

define('MEDIA_IMG_PATH', MEDIA_URL . 'images/');
define('MEDIA_CSS_PATH', MEDIA_URL . 'styles/');
define('MEDIA_JS_PATH', MEDIA_URL . 'scripts/');
define('THEME_PATH', APP_PATH . 'theme/');
define('ADMIN_THEME_PATH', APP_PATH . 'admin/theme/');
define('SSL_ENABLED', false);
?>