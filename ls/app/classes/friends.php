<?php
class friends
{
	private static function setup_db() {
		$db = new db();
		$sql = "CREATE TABLE `user_book_permissions` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `user_id` int(11) DEFAULT NULL,
		  `book_id` int(11) DEFAULT NULL,
		  `name` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `relation` varchar(255) NOT NULL,
		  `status` int(11) DEFAULT NULL,
		  `timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;";
		$db->query( $sql );
	}
	
	public static function invited($user_id, $book_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_permissions WHERE user_id = $user_id AND book_id = $book_id ";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->book_id = $row['book_id'];
				$question->name = $row['name'];
				$question->email = $row['email'];
				$question->relation = $row['relation'];
				$question->status = $row['status'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function by_id($friend_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_permissions WHERE id = $friend_id";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->book_id = $row['book_id'];
				$question->name = $row['name'];
				$question->email = $row['email'];
				$question->relation = $row['relation'];
				$question->status = $row['status'];
				$results[] = $question;
			}
			
		return $results;
	}
	
}
?>