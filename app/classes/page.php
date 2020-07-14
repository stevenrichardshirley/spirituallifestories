<?php 
class page
{
	private static function setup_db() {
		$db = new db();
		$sql = "CREATE TABLE `webpages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$db->query( $sql );
	}

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
		$array = array_filter( $url );

		$bit = array_pop( $array );
		
		$dash = '-';
		$piece = strpos($bit, $dash);
		
		if( $piece === false ) { } else {
			$slug = $bit;
			$bit = array_pop( $array );
		}
        
		switch( $bit ) {
		
			case 'questions' :
				if( !isset($slug) ) {
					$page->template = 'questions';
					$page->content = questions::get_questions();
				} else {
					$page->template = 'question';
					$page->content = $slug;
				}
			break;
			
			case 'category' :
				if( !isset($slug) ) {
					$page->template = 'question_categories';
					$page->content = questions::get_questions();
				} else {
					$page->template = 'questions';
					$page->content = $slug;
				}
			break;
			
			case 'friends_book' :
				if( !isset($slug) ) {
					$page->template = 'friends_categories';
					$page->content = $bit;
				} else {
					$page->template = 'friends_categories';
					$page->content = $slug;
				}
			break;
			
			case 'friends_category' :
				if( !isset($slug) ) {
					$page->template = 'friends_questions';
					$page->content = $bit;
				} else {
					$page->template = 'friends_questions';
					$page->content = $slug;
				}
			break;
			
			case 'friends_question' :
				if( !isset($slug) ) {
					$page->template = 'friends_question';
					$page->content = $bit;
				} else {
					$page->template = 'friends_question';
					$page->content = $slug;
				}
			break;
			
			case 'edit_book' :
				if( !isset($slug) ) {
					$page->template = 'library';
					$page->content = $bit;
				} else {
					$page->template = 'edit_book';
					$page->content = $slug;
				}
			break;
					
			case 'about' :
					$page->template = 'about';
					$page->content = "Content";
			break;
			
			case 'contrib_approve' :
					$page->template = 'contrib_approve';
					$page->content = "Content";
			break;
			
			case 'tell_others' :
					$page->template = 'tell_others';
					$page->content = "Content";
			break;
			
			case 'invite' :
				if( !isset($slug) ) {
					$page->template = 'invite_books';
					$page->content = $bit;
				} else {
					$page->template = 'invite';
					$page->content = $slug;
				}
			break;
			
			case 'write_your_story' :
					$page->template = 'write_story';
					$page->content = "Content";
			break;
			
			case 'testimonials' :
					$page->template = 'testimonials';
					$page->content = "Content";
			break;
			
			case 'free_resources' :
					$page->template = 'free_resources';
					$page->content = "Content";
			break;
			
			case 'we_write_your_story' :
					$page->template = 'we_write_story';
					$page->content = "Content";
			break;
			
			case 'contact' :
					$page->template = 'contact';
					$page->content = "Content";
			break;
			
			case 'login' :
					$page->template = 'login';
					$page->content = "Content";
			break;
			
			case 'logout' :
					$page->template = 'logout';
					$page->content = "Content";
			break;
			
			case 'register' :
					$page->template = 'register';
					$page->content = strlen($slug)>0?$slug:"Content";
			break;
			
			case 'terms_and_conditions' :
					$page->template = 'terms';
					$page->content = "Content";
			break;
			
			case 'forgot_password' :
				if( !isset($slug) ) {
					$page->template = 'forgot_password';
					$page->content = $bit;
				} else {
					$page->template = 'reset_password';
					$page->content = $slug;
				}
			break;
			
			case 'library' :
				if( !isset($slug) ) {
					$page->template = 'library';
					$page->content = "Content";
				} elseif ( $slug == 'new-book' ) {
					$page->template = 'new_book';
					$page->content = "Content";
					$page->slug = $slug;
				} elseif ( $slug == 'my-story' ) {
					$page->template = 'question_categories';
					$page->content = "Content";
					$page->slug = $slug;
				} else {
					$page->template = 'journal';
					$page->content = "Content";
					$page->slug = $slug;
				}
			break;
            case 'myaccount' :
                    $page->template = 'myaccount';
                    $page->content = "Content";
            break;

			default :
					$page->template = 'home';
					$page->content = "Content";
			break;
		}
		
		return $page;
	}
	
	public function content() {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM content ORDER BY sort_order";
		$page = $db->query( $sql );
			while( $row = $db->fetch($page) ) {
				$content = new stdClass();
				$content->id = $row['id'];
				$content->title = $row['title'];
				$content->slug = $row['slug'];
				$content->content = $row['content'];
				$content->image = $row['image'];
				$results[] = $content;
			}
			
		return $results;
	}
	
	public function content_item($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM content WHERE id = {$id} LIMIT 1";
		$page = $db->query( $sql );
			while( $row = $db->fetch($page) ) {
				echo '
				<div class="info_icon">
					<a name="' .$row['slug']. '">
					<img src="/theme/images/content/'. $row['image'] 	. '" class="shadow" alt="' .$row['title']. '" />
					</a>
				</div>
				<div class="holder">
				<strong>' .$row['title']. '</strong><br />
				' .$row['content']. '
				</div>
				';
			}
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
