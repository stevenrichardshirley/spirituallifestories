<?php
class journal
{
	private static function setup_db() {
		$db = new db();
		$sql = "CREATE TABLE `questions` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `category` int(11) DEFAULT NULL,
		  `subcategory` int(11) DEFAULT NULL,
		  `content` text,
		  `timestamp` timestamp NULL DEFAULT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		$db->query( $sql );
	}
	
	public function get_answer($user_id, $entry_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_answers WHERE user_id = $user_id AND question_id = $entry_id";
		$entries = $db->query( $sql );
			while( $row = $db->fetch($entries) ) {
				$entry = new stdClass();
				$entry->id = $row['id'];
				$entry->user_id = $row['user_id'];
				$entry->question_id = $row['question_id'];
				$entry->content = $row['content'];
				$results[] = $entry;
			}
			
		return $results;
	}
	
	public function get_entries($user_id, $book_id, $limit = 5) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_entries WHERE book_id = $book_id AND user_id = $user_id LIMIT $limit";
		$entries = $db->query( $sql );
			while( $row = $db->fetch($entries) ) {
				$entry = new stdClass();
				$entry->id = $row['id'];
				$entry->book_id = $row['book_id'];
				$entry->user_id = $row['user_id'];
				$entry->title = $row['title'];
				$entry->slug = $row['slug'];
				$entry->date = $row['date'];
				$entry->content = $row['content'];
				$entry->tags = $row['tags'];
				$entry->timestamp = $row['timestamp'];
				$results[] = $entry;
			}
			
		return $results;
	}
	
	public function get_entry_by_date($date, $book_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_entries WHERE book_id = $book_id AND date = $date";
		$entries = $db->query( $sql );
			while( $row = $db->fetch($entries) ) {
				$entry = new stdClass();
				$entry->id = $row['id'];
				$entry->book_id = $row['book_id'];
				$entry->user_id = $row['user_id'];
				$entry->title = $row['title'];
				$entry->slug = $row['slug'];
				$entry->date = $row['date'];
				$entry->content = $row['content'];
				$entry->tags = $row['tags'];
				$entry->timestamp = $row['timestamp'];
				$results[] = $entry;
			}
			
		return $results;
	}
	
	public function check_for_entries($user_id, $book_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM user_book_entries WHERE user_id = $user_id AND book_id = $entry_id");
		$answer = $db->num( $sql );
		return $answer;
	}	
	
}
?>