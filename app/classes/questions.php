<?php
class questions
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
	
	public static function insert($question) {
		$db = new db();
		$return = array();
		$sql = "INSERT into questions values('','$question->category', '$question->subcategory', '$question->content', '')";
		if( $db->query( $sql ) ) {
			$return['type'] = 'messagepass';
			$return['message'] = 'question added successfully.';
		} else {
			$return['type'] = 'messagefail';
			$return['message'] = 'There was a problem adding your m, please try again.';
		}
		return $return;
	}
	
	public static function update($question) {
		$return = array();
		$db = new db();
		$sql = 
			"UPDATE questions set 
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
	
	public function get_questions() {
		$db = new db();
		$results = array();
		$defaults = array();
		
		$sql = "SELECT * FROM questions";
		
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->slug = $row['slug'];
				$question->category = $row['category'];
				$question->subcategory = $row['subcategory'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_question($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM questions WHERE id = $id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->slug = $row['slug'];
				$question->category = $row['category'];
				$question->subcategory = $row['subcategory'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_questions_by_category($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM questions WHERE category = $id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->slug = $row['slug'];
				$question->category = $row['category'];
				$question->subcategory = $row['subcategory'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_questions_by_subcategory($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM questions WHERE subcategory = $id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->slug = $row['slug'];
				$question->category = $row['category'];
				$question->subcategory = $row['subcategory'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_answer($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_answers WHERE user_id = $user_id AND question_id = $question_id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_contribution($question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE id = $question_id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$question->timestamp = $row['timestamp'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_answer_friend($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE user_id = $user_id AND question_id = $question_id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_friends_answers($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE friend_id = $user_id AND question_id = $question_id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->friend_id = $row['friend_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$question->timestamp = $row['timestamp'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	
	public function get_friends_answers_unapproved($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE friend_id = $user_id AND question_id = $question_id AND approved = 0";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->friend_id = $row['friend_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$question->timestamp = $row['timestamp'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_friends_answers_approved($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE friend_id = $user_id AND question_id = $question_id AND approved = 1";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->friend_id = $row['friend_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$question->timestamp = $row['timestamp'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_friends_answers_denied($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_contributions WHERE friend_id = $user_id AND question_id = $question_id AND approved = 2";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->friend_id = $row['friend_id'];
				$question->question_id = $row['question_id'];
				$question->content = $row['content'];
				$question->timestamp = $row['timestamp'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function change_contribution_status($id, $status) {
		$return = array();
		$db = new db();
		$sql = "UPDATE question_contributions SET approved = $status WHERE id = $id";
		if( $db->query( $sql ) ) {
			$return['type'] = 'messagepass';
			$return['message'] = 'Question updated successfully.';
		} else {
			$return['type'] = 'messagefail';
			$return['message'] = 'There was a problem updating your question, please try again.';
		}
		return $return;
	}
	
	public function add_contribution($id, $contrib) {
		$return = array();
		$db = new db();
		$sql = "UPDATE question_answers SET content = CONCAT(content, '$contrib') WHERE id = $id";
		if( $db->query( $sql ) ) {
			$return['type'] = 'messagepass';
			$return['message'] = 'Question updated successfully.';
		} else {
			$return['type'] = 'messagefail';
			$return['message'] = 'There was a problem updating your question, please try again.';
		}
		return $return;
	}
	
	public function get_friend($email) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_permissions WHERE email = '$email' LIMIT 1";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->name = $row['name'];
				$question->email = $row['email'];
				$question->relation = $row['relation'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_friend_by_id($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_book_permissions WHERE id = '$id' LIMIT 1";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->name = $row['name'];
				$question->email = $row['email'];
				$question->relation = $row['relation'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_photos($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_photos WHERE user_id = $user_id AND question_id = $question_id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->user_id = $row['user_id'];
				$question->question_id = $row['question_id'];
				$question->image_url = $row['image_url'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function check_for_answer($user_id, $question_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM question_answers WHERE user_id = $user_id AND question_id = $question_id");
		$answer = $db->num( $sql );
		return $answer;
	}
	
	public function check_for_answer_friend($user_id, $question_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM question_contributions WHERE user_id = $user_id AND question_id = $question_id");
		$answer = $db->num( $sql );
		return $answer;
	}
	
	public function check_for_permission($user_id, $question_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM question_permissions WHERE user_id = $user_id AND question_id = $question_id");
		$answer = $db->num( $sql );
		return $answer;
	}
	
	public function get_category($slug) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_categories WHERE slug = '$slug'";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->tagline = $row['tagline'];
				$question->description = $row['description'];
				$question->image_url = $row['image_url'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_category_by_id($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM question_categories WHERE id = $id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->title = $row['title'];
				$question->slug = $row['slug'];
				$question->tagline = $row['tagline'];
				$question->description = $row['description'];
				$question->image_url = $row['image_url'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public function get_categories() {
		$db = new db();
		$results = array();
		$defaults = array();
		
		$sql = "SELECT * FROM question_categories";
		
		$categories = $db->query( $sql );
			while( $row = $db->fetch($categories) ) {
				$category = new stdClass();
				$category->id = $row['id'];
				$category->title = $row['title'];
				$category->slug = $row['slug'];
				$category->tagline = $row['tagline'];
				$category->description = $row['description'];
				$category->image_url = $row['image_url'];
				$results[] = $category;
			}
			
		return $results;
	}
	
	public function get_subcategories($id) {
		$db = new db();
		$results = array();
		$defaults = array();
		
		$sql = "SELECT * FROM question_subcategories WHERE parent = $id";
		
		$categories = $db->query( $sql );
			while( $row = $db->fetch($categories) ) {
				$category = new stdClass();
				$category->id = $row['id'];
				$category->title = $row['title'];
				$category->slug = $row['slug'];
				$category->tagline = $row['tagline'];
				$category->description = $row['description'];
				$category->image_url = $row['image_url'];
				$results[] = $category;
			}
			
		return $results;
	}
	
	public function count_questions() {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM questions WHERE active = 1");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function count_subcategories($id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM question_subcategories WHERE parent = $id");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function count_questions_by_category($id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM questions WHERE category = $id");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function question_answered($user_id, $question_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT * FROM question_answers WHERE user_id = $user_id AND question_id = $question_id AND content IS NOT NULL");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function question_answered_by_friend($user_id, $question_id) {
		$db = new db();
		$results = array();
		$sql = $db->query("SELECT * FROM question_contributions WHERE friend_id = $user_id AND question_id = $question_id AND approved = 0");
		$categories = $db->num( $sql );
		return $categories;
	}

    public function category_with_questions_answered_by_friend($user_id, $category_id) {
        $db = new db();
        $results = array();
        list($qt) = $db->fetchrow($db->query("SELECT count(*) FROM question_contributions qc inner join questions q on qc.question_id=q.id WHERE qc.friend_id = $user_id AND q.category= $category_id AND qc.approved = 0"));
        return $qt;
    }
	
	public function answered_by_category($user_id, $category_id) {
		$db = new db();
		$results = array();
		
		$sql = $db->query("SELECT questions.id, questions.category, questions.content, question_answers.user_id, question_answers.question_id, question_answers.content FROM questions, question_answers WHERE user_id = $user_id AND questions.id = question_answers.question_id AND questions.category = $category_id");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function answered_by_subcategory($user_id, $category_id) {
		$db = new db();
		$results = array();

		$sql = $db->query("SELECT questions.id, questions.subcategory, questions.content, question_answers.user_id, question_answers.question_id, question_answers.content FROM questions, question_answers WHERE user_id = $user_id AND questions.id = question_answers.question_id AND questions.subcategory = $category_id");
		$categories = $db->num( $sql );
		return $categories;
	}
	
	public function total_answered($user_id, $category_id=0) {
		$db = new db();
		$results = array();
		
        $catfilter = $category_id>0?"q.category={$category_id}":'0=0';
        
		list($qt) = mysql_fetch_row(mysql_query("SELECT count(distinct qa.question_id) FROM question_answers qa inner join questions q on qa.question_id=q.id 
                                                        WHERE qa.user_id = $user_id AND $catfilter"));
		return $qt;
	}
	
	
}
?>