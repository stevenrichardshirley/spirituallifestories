<?php
require_once('../app/bootstrap.php'); 
require('db-functions.inc.php');

$user_id = $_POST['user_id'];
$question_id = $_POST['question_id'];
$content = $_POST['content'];

$answer = questions::check_for_answer($user_id, $question_id);

if ( $answer == 0 ) {
	dbQuery("INSERT INTO `question_answers` (`user_id`,`question_id`,`content`) VALUES ('$user_id','$question_id','$content')");
} else {
	dbQuery("UPDATE `question_answers` SET user_id = '{$user_id}', question_id = '{$question_id}', content = '{$content}' WHERE user_id = '{$user_id}' AND question_id = '{$question_id}'");
}
        
?>