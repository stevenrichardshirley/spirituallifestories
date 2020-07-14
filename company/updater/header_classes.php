<?

if (is_array($_POST))
    foreach ($_POST as $x_elemento=>$x_valor)
        $$x_elemento=$x_valor;

if (is_array($_GET))
    foreach ($_GET as $x_elemento=>$x_valor)
        $$x_elemento=$x_valor;

if (is_array($_REQUEST))
    foreach ($_REQUEST as $x_elemento=>$x_valor)
        $$x_elemento=$x_valor;


error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));



define('usuario_master', 'admin');

define('senha_master'  , 'vandelay');



// tipo de login

define('tipo_login_usuario', 1);

define('tipo_login_cliente', 2);

define('tipo_login_master', 3);



// tipo de campo

define('tipo_campo_texto', 1);

define('tipo_campo_checkbox', 2);

define('tipo_campo_multilinha', 3);

define('tipo_campo_color', 4);

define('tipo_campo_arqimagem', 5);

define('tipo_campo_arqmusica', 6);

define('tipo_campo_arqgeral', 7);

define('tipo_campo_data',8);

define('tipo_campo_link',9);



// lista de campos tipo arquivo

define('campos_tipo_arquivo', tipo_campo_arqimagem.','.tipo_campo_arqmusica.','.tipo_campo_arqgeral);



// tipos de acao

define('tipo_acao_menu', 1);

define('tipo_acao_botao', 2);

define('tipo_acao_galeria', 3);

define('tipo_acao_itemgaleria', 4);



// ações

define('acao_sem_acao',1);

define('acao_galeria_fotos', 5);

define('acao_galeria_youtube', 6);

define('acao_galeria_musicas', 7);









function NextCode($t, $c)

{

    $r = mysql_fetch_row(mysql_query("SELECT max($c) FROM $t"));

    return $r[0]+1;

}



include_once('classes/class_funcoes.php');

include_once('classes/class_login.php');





include_once('classes/class_data.php');

include_once('classes/class_usuarios.php');

include_once('classes/class_menu.php');

include_once('classes/class_sites.php');

include_once('classes/class_clientes.php');

include_once('classes/class_itens_site.php');

include_once('classes/class_valores_campos.php');



include_once('classes/class_resultados.php');

include_once('classes/class_idiomas.php');

include_once('classes/class_acoes.php');

include_once('classes/class_tipos_acoes.php');

include_once('classes/class_campos_acoes.php');





session_start();


if (is_array($_SESSION))
    foreach ($_SESSION as $x_elemento=>$x_valor)
        $$x_elemento=$x_valor;

include_once('includes/dbconnect.php');

?>