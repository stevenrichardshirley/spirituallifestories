<?php 
class admin
{
	/**
	 * dispatch function.
	 * 
	 * @access public
	 * @static
	 * @return array
	 */
	public static function dispatch() {
		$db = new db();
		$page = new stdClass();
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$url = $_SERVER['REQUEST_URI'];
		} else {
			$url = $_SERVER['SCRIPT_NAME'];
			if ( isset( $_SERVER['PATH_INFO'] ) ) {
				$url .= $_SERVER['PATH_INFO'];
			}
		}
		
		$url = explode('/', $url);
		$bit = array_pop($url);
		$bit = explode('?', $bit);
		switch( $bit[0] ) {
			case 'books' :
				$books = new stdclass();
				$page->template = 'books';
				$page->content = $books;
			break;
			case 'albums' :
				$albums = new stdclass();
				$page->template = 'albums';
				$page->content = $albums;
			break;
			case 'events' :
				$page->template = 'event';
				$page->content = events::query_events();
			break;
			default :
				$page->template = 'single';
				$page->content = $db->fetch( $db->query("SELECT * FROM webpages WHERE slug = '$bit'") );	
			break;
		}
		
		return $page;
	}
	
	public static function sub_nav($section) {
		$db = new db();
		$return = array();
		$i = 1;
		$sql = "SELECT id, slug, name FROM webpages WHERE section_id = $section ORDER BY ID ASC";
		$results = $db->query( $sql );
		while( $row = $db->fetch($results) ) {
			$link = new stdClass();
			$link->url = utils::url('home') . $row['slug'];
			$link->name = $row['name'];
			$return[] = $link;
			$i++;
		}
		return $return;
	}
}
?>