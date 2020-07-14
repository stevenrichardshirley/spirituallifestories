<?php
require('db-functions.inc.php');

function saveTweets($screen_name) {
    global $link;
    $screen_name = dbEscape(strtolower(trim($screen_name)));
    if (!$screen_name) { echo "<p><strong>Error: No screen name declared.</strong></p>\n"; return false; }

    $row = dbGetRow("SELECT `id` FROM `twitter` WHERE `screen_name`='$screen_name' ORDER BY `id` DESC LIMIT 1");
    $last_id = $row['id'];
    
    $url = "http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=$screen_name";
    if ($last_id) { $url .= "&since_id=$last_id" ; }
    $ch = curl_init($url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $xml = curl_exec ($ch);
    curl_close ($ch);

    $affected = 0;
    $twelement = new SimpleXMLElement($xml);
    if ( isset($twelement['error']) ) { $twelement['error']; }
    foreach ($twelement->status as $status) {
        $text = dbEscape(trim($status->text));
        $time = strtotime($status->created_at);
        $id = $status->id;
        dbQuery("INSERT INTO `twitter` (`id`,`screen_name`,`time`,`text`,`hidden`) VALUES ('$id','$screen_name','$time','$text','n')");
        $affected = $affected + dbAffectedRows();
    }

    return "<p>".number_format($affected)." new tweets from $screen_name saved.</p>\n" ;
}

echo saveTweets('officialseedbed');

?>