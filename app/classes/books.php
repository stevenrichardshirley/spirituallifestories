<?php
class books
{
	private static function setup_db() {
		$db = new db();
		$sql = "CREATE TABLE `user_books` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) DEFAULT NULL,
		  `title` varchar(255) DEFAULT NULL,
		  `slug` varchar(255) DEFAULT NULL,
		  `cover` int(11) DEFAULT NULL,
		  `image_url` varchar(255) DEFAULT NULL,
		  `description` text,
		  `timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$db->query( $sql );
	}
	
	public static function insert($question) {
		$db = new db();
		$return = array();
		$sql = "INSERT into user_books values('','$question->category', '$question->subcategory', '$question->content', '')";
		if( $db->query( $sql ) ) {
			$return['type'] = 'messagepass';
			$return['message'] = 'question added successfully.';
		} else {
			$return['type'] = 'messagefail';
			$return['message'] = 'There was a problem adding your book, please try again.';
		}
		return $return;
	}
	
	public static function make_library($id, $name) {
		$db = new db();
		$return = array();
		$db->query("INSERT into user_books values('','$id', '0', 'The Spiritual Life Story of $name', 'my-story', '1', '', '', '')");
	}
	
	public static function update($question) {
		$return = array();
		$db = new db();
		$sql = 
			"UPDATE user_books set 
						'$question->category',
						'$question->subcategory',
						'$question->content'
					WHERE id = $question->id;
			";
		if( $db->query( $sql ) ) {
			$return['type'] = 'messagepass';
			$return['message'] = 'Question updated successfully.';
		} else {
			$return['type'] = 'messagefail';
			$return['message'] = 'There was a problem updating your question, please try again.';
		}
		return $return;
	}
	
	public static function get_books() {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_books";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function get_cover($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_covers WHERE id = '$id'";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->name = $row['name'];
				$question->image_url = $row['image_url'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function get_covers() {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_covers";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->name = $row['name'];
				$question->image_url = $row['image_url'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function get_library($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_books WHERE user_id = '$id'";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function get_my_story($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_books WHERE slug = 'my-story' AND user_id = '$id' LIMIT 1";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function get_book($user_id, $id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_books WHERE id = '$id' AND user_id = '$user_id' LIMIT 1";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function by_id($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_books WHERE id = '$id' LIMIT 1";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function friends($email) {
		$db = new db();
		$results = array();
		$sql = "SELECT user_books.id, user_books.title, user_books.slug, user_books.cover, user_books.image_url, user_books.description, user_book_permissions.book_id, user_book_permissions.email  FROM user_books, user_book_permissions WHERE user_books.id = user_book_permissions.book_id AND user_book_permissions.email = '$email' GROUP BY user_books.id";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->cover = $row['cover'];
				$question->image_url = $row['image_url'];
				$question->book_id = $row['book_id'];
				$question->description = $row['description'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function invite_exist($user_id, $book_id, $email) {
		$db = new db();
		$return = array();
		$i = 1;
		$sql = "SELECT user_id, book_id, email FROM user_book_permissions WHERE user_id = '$user_id' AND book_id = '$book_id' AND email = '$email'";
		$results = $db->query( $sql );
		if ( mysql_num_rows($results) == 0 ) {
			$exist = "false";
		} else {
			$exist = "true";
		}
		return $exist;
	}
}
?>