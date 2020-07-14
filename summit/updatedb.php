<?php
include('includes/db_conn.php');
include('includes/functions.php');
$db = new db();

foreach($_GET['item'] as $key=>$value) {
    mysql_query("UPDATE products SET sorting_order = '$key' WHERE id ='$value';");
}
?>