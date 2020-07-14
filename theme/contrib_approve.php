<?php
    session_start();
    $user = user::identify();
    if ( !$user ) {
        utils::redirect( utils::url('home') );
    } 
    
    $theme = new theme();
    $page = new page();
    $page = page::dispatch();
    
    $id = $_POST['id'];
    $question = $_POST['question'];
    $status = $_POST['status'];
    $friend_id = $_POST['friend'];
    
    if ( $status == 1 ) {
    
        $approval = questions::change_contribution_status($id, $status);
        
        $answer = questions::get_answer($user->user_id, $question);
        $contribution = questions::get_contribution($id);
        
        $contrib =  $contribution[0]->content;
        $friend = questions::get_friend_by_id($friend_id);
        
        $content = "<p><strong>Contribution provided by: " .$friend[0]->name. ", " .$friend[0]->relation. " on " .date('F d, Y', strtotime($contribution[0]->timestamp))."</strong><p>" .$contrib. "</p></p>";
        
        
        $contribution = questions::add_contribution($answer[0]->id, $content);
    
    } else {
        
        $deny = questions::change_contribution_status($id, $status);
        
    }
?>