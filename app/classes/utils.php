<?php
class utils
{
	/**
	 * twitterify function.
	 * 
	 * @access private
	 * @static
	 * @param mixed $ret
	 * @return string text that is processed to convert twitter handles, hashtags, etc to clickable links.
	 */
	private static function twitterify($ret) {
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $ret);
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" >\\2</a>", $ret);
		$ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" >@\\1</a>", $ret);
		$ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $ret);
		return $ret;
	}
	
	/**
	 * limit function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $string
	 * @param mixed $limit
	 * @param string $break. (default: ".")
	 * @param string $pad. (default: "...")
	 * @return void
	 */
	public static function limit($string, $limit, $break = ".", $pad = "...") {
		$string = strip_tags($string);
		if( strlen($string) <= $limit ) {
			return $string;
		}
		
		if( false !== ($breakpoint = strpos($string, $break, $limit)) ) {
    		if( $breakpoint < strlen($string) - 1 ) {
				$string = substr($string, 0, $breakpoint) . $pad;
    		}
  		}
		return $string;
	}
	
	/**
	 * limit_no_strip function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $string
	 * @param mixed $limit
	 * @param string $break. (default: ".")
	 * @param string $pad. (default: "...")
	 * @return void
	 */
	public static function limit_no_strip($string, $limit, $break = ".", $pad = "...") {
		if( strlen($string) <= $limit ) {
			return $string;
		}
		
		if( false !== ($breakpoint = strpos($string, $break, $limit)) ) {
    		if( $breakpoint < strlen($string) - 1 ) {
				$string = substr($string, 0, $breakpoint) . $pad;
    		}
  		}
		return $string;
	}
	
	/**
	 * url function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $place
	 * @return void
	 */
	public static function url($place) {
		switch($place) {
			case 'home' :
				$url = BASE_URL . '/';
			break;
			case 'theme' :
				$url = BASE_URL . '/theme/';
			break;
			case 'library' :
				$url = BASE_URL . '/library/';
			break;
			case 'self' :
				$url = 'http' . ( ( empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] != 443 ) ? '' : 's' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			break;
			default :
				$url = BASE_URL . '/';
			break;
		}
		return $url;
	}
	
	/**
	 * and_list function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $array
	 * @param string $between. (default: ,')
	 * @param mixed '
	 * @param mixed $between_last. (default: null)
	 * @return void
	 */
	public static function and_list( $array, $between = ', ', $between_last = null ) {
		if ( ! is_array( $array ) ) {
			$array = array( $array );
		}

		if ( $between_last === null ) {
			$between_last = _t( ' and ' );
		}

		$last = array_pop( $array );
		$out = implode(', ', $array );
		$out .= ($out == '') ? $last : $between_last . $last;
		return $out;
	}
	
	/**
	 * redirect function.
	 * 
	 * @access public
	 * @static
	 * @param string $url. (default: '')
	 * @param bool $continue. (default: false)
	 * @return void
	 */
	public static function redirect( $url = '', $continue = false ) {
		if ( $url == '' ) {
			$url = self::url('home');
		}
		
		header( 'Location: ' . $url, true, 302 );
		if ( ! $continue ) exit;
	}
	
	/**
	 * getLastXTwitterStatus function.
	 * 
	 * @access public
	 * @param mixed $userid
	 * @param mixed $x
	 * @return array of tweet objects
	 */
	public function getLastXTwitterStatus( $userid, $x ) {
		$return = array();
		$url = "http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=$userid&count=$x&include_rts=true";
		$xml = simplexml_load_file( $url );
		if ( $xml != '' ) {
		foreach( $xml->status as $status ) {
			$text = self::twitterify( $status->text );
			$date = strtotime( $status->created_at );
			$tweet = new stdClass();
			$tweet->text = utf8_decode( $text );
			$tweet->date = date( "F j", $date );
			$return[] = $tweet;
		}
		} else {
			echo '<div class="royal_tweet"><strong>Fail Whale!</strong> Looks like the Twitter API is down or experiencing a technical issue. Our tweets will show here once Twitter recovers.</div>';
		}
		return $return;
	 }
	 
	public function slug($text)
	{
		$text = preg_replace("/(.*?)([A-Za-z0-9\s]*)(.*?)/", "$2", $text);
		$text = preg_replace('/\%/',' percent',$text); 
		$text = preg_replace('/\@/',' at ',$text); 
		$text = preg_replace('/\&/',' and ',$text); 
		$text = preg_replace('/\s[\s]+/','-',$text);    // Strip off multiple spaces 
		$text = preg_replace('/[\s\W]+/','-',$text);    // Strip off spaces and non-alpha-numeric 
		$text = preg_replace('/^[\-]+/','',$text); // Strip off the starting hyphens 
		$text = preg_replace('/[\-]+$/','',$text); // // Strip off the ending hyphens 
		$text = strtolower($text); 
	
		// trim and lowercase
		$text = strtolower(trim($text, '-'));
		
		if ( strpos($text, '-') ) {
		    $text = $text;
		} else {
		    $text = $text . "-book";
		}	
	
		return $text;
		
	}
	 
	 /**
	  * get_feed function.
	  * 
	  * @access public
	  * @param mixed $feed
	  * @return array of post objects
	  */
	 public function get_feed( $feed ) {
		$return = array();
		$xml = simplexml_load_file( $feed ) or die( 'could not connect' );
		foreach( $xml->channel->item as $post ) {
			$text = $post->text;
			$date = strtotime( $status->created_at );
			$postobj = new stdClass();
			$postobj->text = $post->description;
			$postobj->title = $post->title;
			$postobj->link = $post->link;
			$postobj->date = $post->pubDate;
			$return[] = $postobj;
		}
		return $return;	 	
	 }
	 
	 /**
	 * process function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $string
	 * @return void
	 */
	public static function process($string) {
		return addslashes( trim($string) );
	}
	
	/**
	 * crypt function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $pass
	 * @return void
	 */
	public static function crypt($pass) {
		return sha1( $pass );
	}
	
	public static function activation_hash($salt) {
		$pre = mt_rand(1, 500) . $salt;
		return sha1($pre);
	}
}
?>