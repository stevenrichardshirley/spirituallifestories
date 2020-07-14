<?php 
class theme
{
	/**
	 * load function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $file
	 * @return void
	 */
	public static function load($file) {
		if($file == 'style') {
			self::load_style();
		} else {
			if( file_exists(THEME_PATH . $file . '.php') ) {
				include_once( THEME_PATH . $file . '.php' );
			}
		}
	}
	
	private static function load_style() {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="' . utils::url('home') . 'theme/css/style.css">';
	}
	
	public static function image($path) {
		return utils::url('home') . 'theme/images/' . $path;
	}
	
	public static function category($path) {
		return utils::url('home') . 'theme/images/categories/' . $path;
	}
	
	public static function book($path) {
		return utils::url('home') . 'theme/images/books/' . $path;
	}
	
	public static function template($type) {
		if( $type == 'home' ) {
			include(THEME_PATH . 'home.php');
		} else {
			include(THEME_PATH . $type . '.php');
		}
	}
}
?>