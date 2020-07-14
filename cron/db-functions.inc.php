<?php

////////////////////////////////////
//
// Ink Plant MySQL Helper Functions
// http://www.inkplant.com/code/php-mysql-database-functions.php
//
////////////////////////////////////


$GLOBALS['db_debug'] = false; //set to TRUE if you want database errors to be printed on the screen
$selected_db = 'default'; //which array you want to grab the DB info from

if (!is_array($GLOBALS['db_errors'])) { $GLOBALS['db_errors'] = array(); }

$db = array();
$db['default'] = array();
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = 'root';
$db['default']['database'] = 'life_stories';

$dbcerror = false;
$link = mysql_connect($db[$selected_db]['hostname'], $db[$selected_db]['username'], $db[$selected_db]['password']);
if (!$link) { $dbcerror = true; }
mysql_select_db($db[$selected_db]['database'], $link) or ($dbcerror = true);
if ($dbcerror) {
    if (function_exists('inkDie')) { inkDie('There was a problem connecting to our database.'); }
    else { echo "<p class=\"alert\">There was a problem connecting to our database.</p>"; die(); }
}
unset($db); //We unset this here as a security measure so that it can't be printed out by a malicious script elsewhere. You might have to comment this out if it give you problems, though.

if (!function_exists('dbError')) {
function dbError($error=false,$query=false) {
    echo "<p class=\"alert\">Database error!</p>\n" ;
    if ($GLOBALS['db_debug']) { echo "<p>Query: $query</p>\n<p>Error: $error</p>\n" ; }
    if (function_exists('inkDie')) { inkDie('There was a problem getting information from our database.'); }
    else { echo "<p class=\"alert\">There was a problem getting information from our database.</p>"; die(); }
}}

if (!function_exists('dbQuery')) {
function dbQuery($query,$flink=false,$ignore_errors='default') {
    if (!$flink) { global $link; $flink = $link; }
    if ($ignore_errors === 'default') {
        if (@$GLOBALS['ignore_db_errors']) { $ignore_errors = true; } else { $ignore_errors = false; }
    }
    if ($ignore_errors) {
        $result = mysql_query($query,$flink) or ($error = mysql_error($flink));
        if ($error) {
            if (!is_array(@$GLOBALS['db_errors'])) { $GLOBALS['db_errors'] = array(); }
            $GLOBALS['db_errors'][] = "MySQL Error: ".$error." // Query: ".$query;
        }
    } else { $result = mysql_query($query,$flink) or die(dbError(mysql_error($flink),$query)); }
    return $result;
}}

if (!function_exists('dbGetArray')) {
function dbGetArray($query,$flink=null,$ignore_errors=false) {
    if (!$flink) { global $link; $flink = $link; }
    $result = dbQuery($query,$flink,$ignore_errors);
    $a = array();
    while ($row = mysql_fetch_array($result)) {
        $a[] = $row;
    }
    return $a;
}}

if (!function_exists('dbGetRow')) {
function dbGetRow($query_or_result,$flink=null,$ignore_errors=false) {
    if (!$flink) { global $link; $flink = $link; }
    if ((is_resource($query_or_result)) && (get_resource_type($query_or_result) == 'mysql result')) { $result = $query_or_result; }
    else { $result = dbQuery($query_or_result,$flink,$ignore_errors); }
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    return $row;
}}

if (!function_exists('dbNumRows')) {
function dbNumRows($result) {
    $numrows = mysql_num_rows($result);
    return $numrows;
}}

if (!function_exists('dbAffectedRows')) {
function dbAffectedRows($flink=null) {
    if (!$flink) { global $link; $flink = $link; }
    $arows = mysql_affected_rows($flink);
    return $arows;
}}

if (!function_exists('dbInsertId')) {
function dbInsertId($flink=null) {
    if (!$flink) { global $link; $flink = $link; }
    $insert_id = mysql_insert_id($flink);
    return $insert_id;
}}

if (!function_exists('dbEscape')) {
function dbEscape($string,$flink=null) {
    if (!$flink) { global $link; $flink = $link; }
    if (is_array($string)) { $GLOBALS['db_errors'][] = 'Array passed to dbEscape(): '.print_r($string,true); }
    $string = mysql_real_escape_string($string,$flink);
    return $string;
}}


//includes ' where necessary and handles null values
if (!function_exists('dbPrepare')) {
function dbPrepare($string,$flink=false,$args=array()) {
    if ($string === null) { return 'NULL'; }
    if (@$args['stripslashes']) { $string = stripslashes($string); }
    return '\''.dbEscape($string,$flink).'\'';
}}

?>