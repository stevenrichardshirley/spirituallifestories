<?php 
class admin_theme
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
			if( file_exists(ADMIN_THEME_PATH . $file . '.php') ) {
				include_once( ADMIN_THEME_PATH . $file . '.php' );
			}
		}
	}
	
	private static function load_style() {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="' . utils::url('home') . 'admin/theme/globalstyle.css">';
	}
	
	private static function load_admin_style() {
		echo '<link rel="stylesheet" type="text/css" media="screen" href="' . utils::url('home') . 'admin/theme/adminstyles.css">';
	}
	
	public static function image($path) {
		return utils::url('home') . 'admin/theme/images/' . $path;
	}
	
	public static function flash($path) {
		return utils::url('home') . 'admin/theme/swf/' . $path;
	}
	
	public static function pdf($path) {
		return utils::url('home') . 'admin/theme/pdf/' . $path;
	}
	
	public static function template($type) {
		if( $type == 'home' ) {
			include(ADMIN_THEME_PATH . 'home.php');
		} else {
			include(ADMIN_THEME_PATH . $type . '.php');
		}
	}
}
?>