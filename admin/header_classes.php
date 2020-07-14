<?
// funções dos usuarios
define('master_user', 'admin');
define('master_pass', 'superChad');

function fullUpper($s)
{
    return strtoupper(strtr($s ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));

}

include_once("../app/classes/admin_login.php");
include_once("../app/classes/admin_menu.php");
include_once("../app/classes/admin_data.php");

session_start();                                                                                                                                                                     

define('IS_DEV', true);

include_once("../app/classes/database_config.php");
include_once("../app/classes/db.php");

$db = new db();
?>
